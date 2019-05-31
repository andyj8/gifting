<?php

namespace Gifting\Test\Domain\Delivery\Event;

use Gifting\Domain\Delivery\Event\GiftDeliveredEvent;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class GiftDeliveredEventTest extends PHPUnit_Framework_TestCase
{
    public function testHasName()
    {
        $gift = m::mock('Gifting\Domain\Gift\Gift');
        $event = new GiftDeliveredEvent($gift);
        $this->assertEquals(GiftDeliveredEvent::NAME, $event->getName());
    }

    public function testStoresGift()
    {
        $gift = m::mock('Gifting\Domain\Gift\Gift');
        $event = new GiftDeliveredEvent($gift);
        $this->assertEquals($gift, $event->getGift());
    }
}
