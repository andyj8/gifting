<?php

namespace Gifting\Domain\Gift;

use Gifting\Domain\Gift\Redemption\Redemption;
use Gifting\Domain\Person\PostalAddress;
use Gifting\Domain\Person\Recipient;
use Gifting\Domain\Person\Sender;
use Gifting\Domain\Product\Product;
use Gifting\Domain\Voucher\VoucherFactory;
use Gifting\Dto;

class GiftFactory
{
    /**
     * @var VoucherFactory
     */
    private $voucherFactory;

    /**
     * @param VoucherFactory  $voucherFactory
     */
    public function __construct(VoucherFactory $voucherFactory)
    {
        $this->voucherFactory  = $voucherFactory;
    }

    /**
     * @param Dto\GiftDto $giftDto
     *
     * @return Gift
     */
    public function create(Dto\GiftDto $giftDto)
    {
        $specDto   = $giftDto->specification;
        $senderDto = $giftDto->sender;

        return new Gift(
            new GiftId($giftDto->id),
            new Sender($senderDto->name, $senderDto->email, $senderDto->order_id),
            $this->createRecipient($specDto->type, $giftDto->recipient),
            Product::fromDto($giftDto->product),
            GiftSpecification::fromDto($specDto),
            $this->voucherFactory->createVoucher($giftDto),
            $this->createRedemption($giftDto)
        );
    }

    /**
     * @param string $type
     * @param Dto\RecipientDto $recipientDto
     *
     * @return Recipient
     */
    private function createRecipient($type, Dto\RecipientDto $recipientDto)
    {
        $postalAddress = null;

        if ($type === GiftSpecification::TYPE_PHYSICAL) {
            $postalAddress = new PostalAddress(
                $recipientDto->line1,
                $recipientDto->town,
                $recipientDto->postcode
            );
        }

        return new Recipient($recipientDto->name, $recipientDto->email, $postalAddress);
    }

    /**
     * @param Dto\GiftDto $giftDto
     *
     * @return Redemption|null
     */
    private function createRedemption(Dto\GiftDto $giftDto)
    {
        if (!$giftDto->redemption) {
            return null;
        }

        return new Redemption(
            $giftDto->redemption->redeemed_at,
            $giftDto->redemption->client_ip
        );
    }
}
