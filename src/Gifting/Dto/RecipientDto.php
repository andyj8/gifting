<?php

namespace Gifting\Dto;

class RecipientDto
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $line1;

    /**
     * @var string
     */
    public $town;

    /**
     * @var string
     */
    public $postcode;

    /**
     * @var string
     */
    public $email;

    /**
     * @param \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->name)) {
            $this->name = $params->name;
        }
        if (isset($params->line1)) {
            $this->line1 = $params->line1;
        }
        if (isset($params->town)) {
            $this->town = $params->town;
        }
        if (isset($params->postcode)) {
            $this->postcode = $params->postcode;
        }
        if (isset($params->email)) {
            $this->email = $params->email;
        }
    }
}
