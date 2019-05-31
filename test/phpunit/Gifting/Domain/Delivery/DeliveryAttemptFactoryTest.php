<?php

namespace Gifting\Test\Domain\Delivery;

use Gifting\Domain\Delivery\DeliveryAttemptFactory;
use Gifting\Domain\Delivery\Transport\DeliveryDto;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class DeliveryAttemptFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreatesDeliveryAttemptFromDto()
    {
        $gift = m::mock('Gifting\Domain\Gift\Gift');

        $dto = new DeliveryDto();
        $dto->request  = 'request';
        $dto->response = 'response';
        $dto->success  = true;

        $factory = new DeliveryAttemptFactory();
        $deliveryAttempt = $factory->createDeliveryAttempt($gift, $dto);

        $this->assertEquals($gift, $deliveryAttempt->getGift());
        $this->assertEquals('request', $deliveryAttempt->getRequest());
        $this->assertEquals('response', $deliveryAttempt->getResponse());
        $this->assertTrue($deliveryAttempt->wasSuccessful());
    }
}
