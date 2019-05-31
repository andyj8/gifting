<?php

namespace Gifting\Infrastructure\Delivery;

use Gifting\Domain\Delivery\GiftDeliverer;
use Gifting\Domain\Delivery\GiftPostbox;
use Gifting\Domain\Gift\Gift;

class InMemoryGiftPostbox implements GiftPostbox
{
    /**
     * @var GiftDeliverer
     */
    private $giftDeliverer;

    /**
     * @param GiftDeliverer $giftDeliverer
     */
    public function __construct(GiftDeliverer $giftDeliverer)
    {
        $this->giftDeliverer = $giftDeliverer;
    }

    /**
     * @param Gift $gift
     */
    public function post(Gift $gift)
    {
        $this->giftDeliverer->deliver($gift);
    }
}
