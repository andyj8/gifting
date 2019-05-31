<?php

namespace Gifting\Domain\Person;

use InvalidArgumentException;

class PostalAddress
{
    /**
     * @var string
     */
    private $line1;

    /**
     * @var string
     */
    private $town;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @param string $line1
     * @param string $town
     * @param string $postcode
     */
    public function __construct($line1, $town, $postcode)
    {
        if (empty($line1) || !is_string($line1)) {
            throw new InvalidArgumentException('Invalid address line 1');
        }
        if (empty($town) || !is_string($town)) {
            throw new InvalidArgumentException('Invalid town');
        }
        if (empty($postcode) || !is_string($postcode)) {
            throw new InvalidArgumentException('Invalid postcode');
        }

        $this->line1 = $line1;
        $this->town = $town;
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }
}
