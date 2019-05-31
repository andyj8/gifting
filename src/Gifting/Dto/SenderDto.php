<?php

namespace Gifting\Dto;

class SenderDto
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $order_id;

    /**
     * @param \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->name)) {
            $this->name = $params->name;
        }
        if (isset($params->email)) {
            $this->email = $params->email;
        }
        if (isset($params->order_id)) {
            $this->order_id = $params->order_id;
        }
    }
}
