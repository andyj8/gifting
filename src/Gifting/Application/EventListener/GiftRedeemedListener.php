<?php

namespace Gifting\Application\EventListener;

use Gifting\Domain\Gift\Event\GiftRedeemedEvent;
use Psr\Log\LoggerInterface as Logger;

class GiftRedeemedListener
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GiftRedeemedEvent $event
     */
    public function onGiftRedeemed(GiftRedeemedEvent $event)
    {
        $this->logger->debug('Gift redeemed');
    }
}

