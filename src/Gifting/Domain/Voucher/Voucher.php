<?php

namespace Gifting\Domain\Voucher;

use DateTime;
use Gifting\Dto\VoucherDto;
use InvalidArgumentException;

class Voucher
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var DateTime
     */
    private $expiry;

    /**
     * @param string $code
     * @param DateTime $expiry
     */
    public function __construct($code, DateTime $expiry)
    {
        if (empty($code) || !is_string($code)) {
            throw new InvalidArgumentException('Invalid voucher code');
        }

        $this->code = $code;
        $this->expiry = $expiry;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return DateTime
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * @return boolean
     */
    public function hasExpired()
    {
        return $this->expiry < new DateTime();
    }

    /**
     * @return VoucherDto
     */
    public function toDto()
    {
        $dto = new VoucherDto();
        $dto->code = $this->code;
        $dto->expiry = $this->expiry;

        return $dto;
    }
}
