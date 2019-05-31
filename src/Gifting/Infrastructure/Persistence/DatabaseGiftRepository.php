<?php

namespace Gifting\Infrastructure\Persistence;

use DateTime;
use Doctrine\DBAL\Connection;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftFactory;
use Gifting\Domain\Gift\GiftRepository;
use Gifting\Dto\GiftDto;
use Gifting\Dto\GiftSpecificationDto;
use Gifting\Dto\ProductDto;
use Gifting\Dto\RecipientDto;
use Gifting\Dto\RedemptionDto;
use Gifting\Dto\SenderDto;
use Gifting\Dto\VoucherDto;
use PDO;
use stdClass;

class DatabaseGiftRepository implements GiftRepository
{
    /**
     * @var Connection
     */
    private $dbh;

    /**
     * @var GiftFactory
     */
    private $giftFactory;

    /**
     * @param Connection $dbh
     * @param GiftFactory $giftFactory
     */
    public function __construct(Connection $dbh, GiftFactory $giftFactory)
    {
        $this->dbh = $dbh;
        $this->giftFactory = $giftFactory;
    }

    /**
     * @param $id
     *
     * @return Gift
     */
    public function getById($id)
    {
        return $this->getByScalar('id', $id);
    }

    /**
     * @param string $voucherCode
     *
     * @return Gift
     */
    public function getByVoucherCode($voucherCode)
    {
        return $this->getByScalar('voucher_code', $voucherCode);
    }

    /**
     * @param string $column
     * @param string $value
     *
     * @return Gift|null
     */
    private function getByScalar($column, $value)
    {
        $row = $this->dbh->createQueryBuilder()
            ->select('g.*', 'r.*')
            ->from('gifts', 'g')
            ->leftJoin('g', 'redemptions', 'r', 'g.id = r.gift_id')
            ->where('g.' . $column . ' = ?')
            ->setParameter(0, $value)
            ->execute()
            ->fetch(PDO::FETCH_OBJ);

        if (!$row) {
            return null;
        }

        return $this->giftFactory->create($this->createDto($row));
    }

    /**
     * @param DateTime $when
     *
     * @return Gift[]
     */
    public function findDueForDeliveryOn(DateTime $when)
    {
        $rows = $this->dbh->createQueryBuilder()
            ->select('g.*, r.*')
            ->from('gifts', 'g')
            ->leftJoin('g', 'deliveries',  'd', 'g.id = d.gift_id AND d.success = TRUE')
            ->leftJoin('g', 'redemptions', 'r', 'g.id = r.gift_id')
            ->where('g.delivery_date = :date')
            ->andWhere('d.id is null')
            ->andWhere('r.redeemed_at is null')
            ->setParameter('date', $when->format('Y-m-d'))
            ->execute()
            ->fetchAll(PDO::FETCH_OBJ);

        $gifts = [];
        foreach ($rows as $row) {
            $gifts[] = $this->giftFactory->create($this->createDto($row));
        }

        return $gifts;
    }

    /**
     * @param Gift $gift
     */
    public function save(Gift $gift)
    {
        $values = [
            'id'             => $gift->getId()->asString(),
            'type'           => $gift->getSpecification()->getType(),
            'voucher_code'   => $gift->getVoucher()->getCode(),
            'voucher_expiry' => $gift->getVoucher()->getExpiry()->format('Y-m-d'),
            'sender'         => json_encode($gift->getSender()->toDto()),
            'recipient'      => json_encode($gift->getRecipient()->toDto()),
            'product'        => json_encode($gift->getProduct()->toDto()),
            'delivery_date'  => $gift->getSpecification()->getDeliveryDate()->format('Y-m-d'),
            'message'        => $gift->getSpecification()->getMessage(),
            'style_ref'      => $gift->getSpecification()->getStyleRef()
        ];

        $exists = $this->getById($gift->getId()->asString());

        if ($exists) {
            $this->dbh->update('gifts', $values, ['id' => $gift->getId()->asString()]);
        } else {
            $values['created'] = (new DateTime())->format('Y-m-d H:i:s');
            $this->dbh->insert('gifts', $values);
        }

        if ($gift->isRedeemed() && !$this->redemptionAlreadyStored($gift)) {
            $this->dbh->insert('redemptions', [
                'redeemed_at' => $gift->getRedemption()->getRedeemedAt()->format('Y-m-d H:i:s'),
                'gift_id'     => $gift->getId()->asString(),
                'client_ip'   => $gift->getRedemption()->getClientIp()
            ]);
        }
    }

    /**
     * @return integer
     */
    public function nextIdentity()
    {
        return $this->dbh->query("SELECT nextval('gifts_id_seq')")->fetchColumn();
    }

    /**
     * @param stdClass $row
     *
     * @return GiftDto
     */
    private function createDto(stdClass $row)
    {
        $giftDto = (new GiftDto())
            ->setSender(new SenderDto(json_decode($row->sender)))
            ->setRecipient(new RecipientDto(json_decode($row->recipient)))
            ->setProduct(new ProductDto(json_decode($row->product)))
            ->setSpecification(new GiftSpecificationDto($row))
            ->setVoucher(new VoucherDto($row))
            ->setId($row->id);

        if ($row->redeemed_at) {
            $giftDto->setRedemption(new RedemptionDto($row));
        }

        return $giftDto;
    }

    /**
     * @param Gift $gift
     *
     * @return boolean
     */
    private function redemptionAlreadyStored(Gift $gift)
    {
        return $this->dbh->createQueryBuilder()
            ->select('r.*')
            ->from('redemptions', 'r')
            ->where('r.gift_id = ?')
            ->setParameter(0, $gift->getId()->asString())
            ->execute()
            ->fetch(PDO::FETCH_OBJ);
    }
}
