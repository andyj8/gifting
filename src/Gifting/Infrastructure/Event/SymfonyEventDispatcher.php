<?php

namespace Gifting\Infrastructure\Event;

use Gifting\Domain\Event\Event;
use Gifting\Domain\Event\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SymfonyEventDispatcher implements EventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    private $symfonyEventDispatcher;

    /**
     * @param EventDispatcherInterface $symfonyEventDispatcher
     */
    public function __construct(EventDispatcherInterface $symfonyEventDispatcher)
    {
        $this->symfonyEventDispatcher = $symfonyEventDispatcher;
    }

    /**
     * @param Event $event
     *
     * @return mixed
     */
    public function dispatch(Event $event)
    {
        $listeners = $this->symfonyEventDispatcher->getListeners($event->getName());

        foreach ($listeners as $listener) {
            call_user_func($listener, $event);
        }
    }
}
