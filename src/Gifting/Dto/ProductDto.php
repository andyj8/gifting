<?php

namespace Gifting\Dto;

class ProductDto
{
    /**
     * @var string
     */
    public $sku;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $image_url;

    /**
     * @param \stdClass $params
     */
    public function __construct(\stdClass $params = null)
    {
        if (isset($params->sku)) {
            $this->sku = $params->sku;
        }
        if (isset($params->type)) {
            $this->type = $params->type;
        }
        if (isset($params->name)) {
            $this->name = $params->name;
        }
        if (isset($params->image_url)) {
            $this->image_url = $params->image_url;
        }
    }
}
