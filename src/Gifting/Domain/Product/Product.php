<?php

namespace Gifting\Domain\Product;

use Gifting\Dto\ProductDto;
use InvalidArgumentException;

class Product
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @param string $sku
     * @param string $name
     * @param string $type
     * @param string $imageUrl
     */
    public function __construct($sku, $name, $type, $imageUrl)
    {
        if (empty($sku) || !is_string($sku)) {
            throw new InvalidArgumentException('Product SKU required');
        }
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('Product name required');
        }
        if (empty($type) || !is_string($type)) {
            throw new InvalidArgumentException('Product type required');
        }
        if (empty($imageUrl) || !is_string($imageUrl)) {
            throw new InvalidArgumentException('Product image url required');
        }

        $this->sku = $sku;
        $this->name = $name;
        $this->type = $type;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param ProductDto $productDto
     *
     * @return Product
     */
    public static function fromDto(ProductDto $productDto)
    {
        return new self(
            $productDto->sku,
            $productDto->name,
            $productDto->type,
            $productDto->image_url
        );
    }

    /**
     * @return ProductDto
     */
    public function toDto()
    {
        $dto = new ProductDto();
        $dto->sku = $this->sku;
        $dto->name = $this->name;
        $dto->type = $this->type;
        $dto->image_url = $this->imageUrl;

        return $dto;
    }
}
