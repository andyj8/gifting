<?php

namespace Gifting\Domain\Voucher;

use Gifting\Dto\GiftDto;

interface VoucherFactory
{
    /**
     * @param GiftDto $giftDto
     *
     * @return Voucher
     */
    public function createVoucher(GiftDto $giftDto);
}
