<?php

namespace Gifting\Dto;

use DateTime;

class RedemptionDto
{
    /**
     * @var string
     */
    public $redeemed_at;

    /**
     * @var string
     */
    public $client_ip;

    /**
     * @param \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->redeemed_at)) {
            $this->redeemed_at = new DateTime($params->redeemed_at);
        }
        if (isset($params->client_ip)) {
            $this->client_ip = $params->client_ip;
        }
    }
}
