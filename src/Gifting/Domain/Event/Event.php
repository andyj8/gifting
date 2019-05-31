<?php

namespace Gifting\Domain\Event;

interface Event
{
    /**
     * @return string
     */
    public function getName();
}
