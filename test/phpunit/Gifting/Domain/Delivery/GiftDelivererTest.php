<?php

namespace Gifting\Test\Domain\Delivery;

use Gifting\Domain\Delivery\GiftDeliverer;
use Gifting\Domain\Delivery\Transport\TransportFactory;
use Gifting\Infrastructure\Delivery\Transport\HealthyTransport;
use Gifting\Infrastructure\Delivery\Transport\UnwellTransport;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class GiftDelivererTest extends PHPUnit_Framework_TestCase
{
    public function testSuccessfulDeliveryIsRecorded()
    {
        $transportFactory = new TransportFactory([new HealthyTransport()]);

        $deliveryRepository = m::mock('Gifting\Domain\Delivery\DeliveryRepository');
        $deliveryRepository->shouldReceive('save');

        $eventDispatcher = m::mock('Gifting\Domain\Event\EventDispatcher');
        $eventDispatcher->shouldIgnoreMissing();

        $deliverer = new GiftDeliverer($transportFactory, $deliveryRepository, $eventDispatcher);
        $deliverer->deliver(m::mock('Gifting\Domain\Gift\Gift'));
    }

    public function testSuccessfulDeliveryDispatchesDeliveredEvent()
    {
        $transportFactory = new TransportFactory([new HealthyTransport()]);

        $deliveryRepository = m::mock('Gifting\Domain\Delivery\DeliveryRepository');
        $deliveryRepository->shouldIgnoreMissing();

        $eventDispatcher = m::mock('Gifting\Domain\Event\EventDispatcher');
        $eventDispatcher->shouldReceive('dispatch');

        $deliverer = new GiftDeliverer($transportFactory, $deliveryRepository, $eventDispatcher);
        $deliverer->deliver(m::mock('Gifting\Domain\Gift\Gift'));
    }

    public function testFailedDeliveryThrowsException()
    {
        $this->setExpectedException('Gifting\Domain\Delivery\Exception\GiftDeliveryFailedException');

        $transportFactory = new TransportFactory([new UnwellTransport()]);

        $deliveryRepository = m::mock('Gifting\Domain\Delivery\DeliveryRepository');
        $deliveryRepository->shouldIgnoreMissing();

        $eventDispatcher = m::mock('Gifting\Domain\Event\EventDispatcher');
        $deliveryRepository->shouldIgnoreMissing();

        $deliverer = new GiftDeliverer($transportFactory, $deliveryRepository, $eventDispatcher);
        $deliverer->deliver(m::mock('Gifting\Domain\Gift\Gift'));
    }
}
