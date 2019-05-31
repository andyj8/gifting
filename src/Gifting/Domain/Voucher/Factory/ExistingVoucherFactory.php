<?php

namespace Gifting\Domain\Voucher\Factory;

use Gifting\Domain\Voucher\Voucher;
use Gifting\Domain\Voucher\VoucherFactory;
use Gifting\Dto\GiftDto;

class ExistingVoucherFactory implements VoucherFactory
{
    /**
     * @param GiftDto $giftDto
     *
     * @return Voucher
     */
    public function createVoucher(GiftDto $giftDto)
    {
        return new Voucher($giftDto->voucher->code, $giftDto->voucher->expiry);
    }
}
