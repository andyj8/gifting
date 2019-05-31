<?php

namespace Gifting\Domain\Voucher;

class VoucherCodeConfig
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var array
     */
    private $prefixMap;

    /**
     * @var string
     */
    private $availableChars;

    /**
     * @param string $format
     * @param array $prefixMap
     * @param string $availableChars
     */
    public function __construct($format, array $prefixMap, $availableChars)
    {
        $this->format = $format;
        $this->prefixMap = $prefixMap;
        $this->availableChars = $availableChars;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $productType
     *
     * @return array
     */
    public function getPrefixByProductType($productType)
    {
        return $this->prefixMap[$productType];
    }

    /**
     * @return array
     */
    public function getAvailableChars()
    {
        return $this->availableChars;
    }
}
