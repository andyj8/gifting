<?php

namespace Gifting\Dto;

use DateTime;

class VoucherDto
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var DateTime
     */
    public $expiry;

    /**
     * @params \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->voucher_code)) {
            $this->code = $params->voucher_code;
        }
        if (isset($params->voucher_expiry)) {
            $this->expiry = new DateTime($params->voucher_expiry);
        }
    }
}
