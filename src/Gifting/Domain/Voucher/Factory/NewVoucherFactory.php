<?php

namespace Gifting\Domain\Voucher\Factory;

use DateInterval;
use DateTime;
use Gifting\Domain\Voucher\Voucher;
use Gifting\Domain\Voucher\VoucherCodeGenerator;
use Gifting\Domain\Voucher\VoucherFactory;
use Gifting\Dto\GiftDto;

class NewVoucherFactory implements VoucherFactory
{
    /**
     * @var integer
     */
    private $lifeTimeInDays;

    /**
     * @var VoucherCodeGenerator
     */
    private $codeGenerator;

    /**
     * @param integer $lifeTimeInDays
     * @param VoucherCodeGenerator $codeGenerator
     */
    public function __construct($lifeTimeInDays, VoucherCodeGenerator $codeGenerator)
    {
        $this->lifeTimeInDays = $lifeTimeInDays;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param GiftDto $giftDto
     *
     * @return Voucher
     */
    public function createVoucher(GiftDto $giftDto)
    {
        $code = $this->codeGenerator->createUniqueCode($giftDto->product->type);

        $now = new DateTime();
        $expires = $now->add(new DateInterval('P' . $this->lifeTimeInDays . 'D'));

        return new Voucher($code, $expires);
    }
}
