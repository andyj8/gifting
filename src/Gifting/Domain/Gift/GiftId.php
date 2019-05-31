<?php

namespace Gifting\Domain\Gift;

class GiftId
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function asString()
    {
        return (string) $this->id;
    }
}
