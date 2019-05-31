<?php

namespace Gifting\Infrastructure\Delivery;

use Gifting\Domain\Delivery\GiftPostbox;
use Gifting\Domain\Gift\Gift;
use Messaging\Exchange;
use Messaging\Message;

class RabbitGiftPostbox implements GiftPostbox
{
    /**
     * @var Exchange
     */
    private $postboxExchange;

    /**
     * @param Exchange $postboxExchange
     */
    public function __construct(Exchange $postboxExchange)
    {
        $this->postboxExchange = $postboxExchange;
    }

    /**
     * @param Gift $gift
     */
    public function post(Gift $gift)
    {
        $message = new Message(['id' => $gift->getId()->asString()]);
        $this->postboxExchange->publish($message);
    }
}
