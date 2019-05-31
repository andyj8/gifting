<?php

namespace Gifting\Test\Domain\Delivery\Transport;

use Gifting\Domain\Delivery\Transport\TransportFactory;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class TransportFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testReturnsAppropriateTransport()
    {
        $transport1 = m::mock('Gifting\Domain\Delivery\Transport\DeliveryTransport');
        $transport1->shouldReceive('supports')->andReturn(false);

        $transport2 = m::mock('Gifting\Domain\Delivery\Transport\DeliveryTransport');
        $transport2->shouldReceive('supports')->andReturn(true);

        $factory = new TransportFactory([$transport1, $transport2]);
        $gift = m::mock('Gifting\Domain\Gift\Gift');

        $this->assertEquals($transport2, $factory->getTransportFor($gift));
    }

    public function testThrowsExceptionWhenNoSuitableTransport()
    {
        $this->setExpectedException('Gifting\Domain\Delivery\Exception\NoSuitableTransportException');

        $transport = m::mock('Gifting\Domain\Delivery\Transport\DeliveryTransport');
        $transport->shouldReceive('supports')->andReturn(false);

        $factory = new TransportFactory([$transport]);
        $gift = m::mock('Gifting\Domain\Gift\Gift');
        $factory->getTransportFor($gift);
    }
}
