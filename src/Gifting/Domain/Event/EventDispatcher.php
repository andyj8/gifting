<?php

namespace Gifting\Domain\Event;

interface EventDispatcher
{
    /**
     * @param Event $event
     *
     * @return mixed
     */
    public function dispatch(Event $event);
}
