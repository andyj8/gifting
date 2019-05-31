<?php

namespace Gifting\Dto;

use DateTime;
use JsonSerializable;

class GiftSpecificationDto implements JsonSerializable
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $style_ref;

    /**
     * @var string
     */
    public $message;

    /**
     * @var DateTime
     */
    public $delivery_date;

    /**
     * @param \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->type)) {
            $this->type = $params->type;
        }
        if (isset($params->style_ref)) {
            $this->style_ref = $params->style_ref;
        }
        if (isset($params->message)) {
            $this->message = $params->message;
        }

        if (isset($params->delivery_date)) {
            $this->delivery_date = new DateTime($params->delivery_date);
        } else {
            $this->delivery_date = new DateTime();
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = get_object_vars($this);
        $data['delivery_date'] = $this->delivery_date->format('Y-m-d');

        return $data;
    }
}
