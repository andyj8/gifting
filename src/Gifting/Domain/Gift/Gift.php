<?php

namespace Gifting\Domain\Gift;

use DateTime;
use Gifting\Domain\Gift\Exception\GiftAlreadyRedeemedException;
use Gifting\Domain\Gift\Exception\GiftExpiredException;
use Gifting\Domain\Gift\Redemption\Redemption;
use Gifting\Domain\Person\Recipient;
use Gifting\Domain\Person\Sender;
use Gifting\Domain\Product\Product;
use Gifting\Domain\Voucher\Voucher;
use Gifting\Dto\GiftDto;

class Gift
{
    /**
     * @var GiftId
     */
    private $id;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var Voucher
     */
    private $voucher;

    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var GiftSpecification
     */
    private $specification;

    /**
     * @var Redemption
     */
    private $redemption;

    /**
     * @param GiftId $id
     * @param Sender $sender
     * @param Recipient $recipient
     * @param Product $product
     * @param GiftSpecification $specification
     * @param Voucher $voucher
     * @param Redemption $redemption
     */
    public function __construct(
        GiftId $id,
        Sender $sender,
        Recipient $recipient,
        Product $product,
        GiftSpecification $specification,
        Voucher $voucher,
        Redemption $redemption = null
    ) {
        $this->id = $id;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->product = $product;
        $this->specification = $specification;
        $this->voucher = $voucher;
        $this->redemption = $redemption;
    }

    /**
     * @return GiftId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return Voucher
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return GiftSpecification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return boolean
     */
    public function shouldSendToday()
    {
        $today = (new DateTime())->format('Y-m-d');

        return $this->specification->getDeliveryDate()->format('Y-m-d') === $today;
    }

    /**
     * @return boolean
     */
    public function isRedeemed()
    {
        return $this->redemption !== null;
    }

    /**
     * @return boolean
     */
    public function hasExpired()
    {
        return $this->voucher->hasExpired();
    }

    /**
     * @throws GiftAlreadyRedeemedException
     * @throws GiftExpiredException
     */
    public function validateRedeemable()
    {
        if ($this->isRedeemed()) {
            throw new GiftAlreadyRedeemedException();
        }

        if ($this->hasExpired()) {
            throw new GiftExpiredException();
        }
    }

    /**
     * @param string $clientIp
     *
     * @throws GiftAlreadyRedeemedException
     * @throws GiftExpiredException
     */
    public function redeem($clientIp)
    {
        $this->validateRedeemable();

        $this->redemption = new Redemption(new DateTime(), $clientIp);
    }

    /**
     * @return Redemption
     */
    public function getRedemption()
    {
        return $this->redemption;
    }

    /**
     * @return GiftDto
     */
    public function toDto()
    {
        $giftDto = (new GiftDto())
            ->setSender($this->sender->toDto())
            ->setRecipient($this->recipient->toDto())
            ->setProduct($this->product->toDto())
            ->setSpecification($this->specification->toDto())
            ->setVoucher($this->voucher->toDto())
            ->setId($this->id->asString());

        if ($this->redemption) {
            $giftDto->setRedemption($this->redemption->toDto());
        }

        return $giftDto;
    }
}
