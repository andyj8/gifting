<?php

namespace Gifting\Domain\Delivery\Event;

use Gifting\Domain\Event\Event;
use Gifting\Domain\Gift\Gift;

class GiftDeliveredEvent implements Event
{
    const NAME = 'gift.delivered';

    /**
     * @var Gift
     */
    private $gift;

    /**
     * @param Gift $gift
     */
    public function __construct(Gift $gift)
    {
        $this->gift = $gift;
    }

    /**
     * @return Gift
     */
    public function getGift()
    {
        return $this->gift;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
