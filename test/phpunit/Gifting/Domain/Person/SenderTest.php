<?php

namespace Gifting\Test\Domain\Person;

use Gifting\Domain\Person\Sender;
use PHPUnit_Framework_TestCase;

class SenderTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresName()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Sender(null, 'value', 'value');
    }

    public function testRequiresEmail()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Sender('value', null, 'value');
    }

    public function testRequiresOrderId()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Sender('value', 'value', null);
    }

    public function testConvertsToDto()
    {
        $sender = new Sender('a', 'b', 'c');

        $dto = $sender->toDto();
        $this->assertEquals('a', $dto->name);
        $this->assertEquals('b', $dto->email);
        $this->assertEquals('c', $dto->order_id);
    }
}
