<?php

namespace Gifting\Application\UseCase\Command;

use Exception;

class RetrieveGiftCommand
{
    /**
     * @var string
     */
    private $voucherCode;

    /**
     * @param $voucherCode
     *
     * @throws Exception
     */
    public function __construct($voucherCode)
    {
        if (empty($voucherCode)) {
            throw new Exception('Voucher code missing');
        }

        $this->voucherCode = $voucherCode;
    }

    /**
     * @return string
     */
    public function getVoucherCode()
    {
        return $this->voucherCode;
    }
}
