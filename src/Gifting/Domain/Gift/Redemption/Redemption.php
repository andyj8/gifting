<?php

namespace Gifting\Domain\Gift\Redemption;

use DateTime;
use Gifting\Dto\RedemptionDto;

class Redemption
{
    /**
     * @var DateTime
     */
    private $redeemedAt;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @param DateTime $redeemedAt
     * @param string $clientIp
     */
    public function __construct(DateTime $redeemedAt, $clientIp)
    {
        $this->redeemedAt = $redeemedAt;
        $this->clientIp = $clientIp;
    }

    /**
     * @return DateTime
     */
    public function getRedeemedAt()
    {
        return $this->redeemedAt;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @return RedemptionDto
     */
    public function toDto()
    {
        return new RedemptionDto($this->getRedeemedAt(), $this->clientIp);
    }
}
