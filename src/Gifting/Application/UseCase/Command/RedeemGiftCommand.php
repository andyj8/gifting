<?php

namespace Gifting\Application\UseCase\Command;

use Exception;

class RedeemGiftCommand
{
    /**
     * @var string
     */
    private $voucherCode;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @param $voucherCode
     * @param $clientIp
     *
     * @throws Exception
     */
    public function __construct($voucherCode, $clientIp)
    {
        if (empty($voucherCode)) {
            throw new Exception('Voucher code missing');
        }
        if (empty($clientIp)) {
            throw new Exception('Client IP missing');
        }

        $this->voucherCode = $voucherCode;
        $this->clientIp = $clientIp;
    }

    /**
     * @return string
     */
    public function getVoucherCode()
    {
        return $this->voucherCode;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }
}
