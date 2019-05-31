<?php

namespace Gifting\Test\Domain\Person;

use Gifting\Domain\Person\PostalAddress;
use Gifting\Domain\Person\Recipient;
use PHPUnit_Framework_TestCase;

class RecipientTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresName()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Recipient(null, 'value');
    }

    public function testRequiresAddress()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Recipient('value');
    }

    public function testConvertsToDto()
    {
        $recipient = new Recipient('name', 'email');

        $dto = $recipient->toDto();
        $this->assertEquals('name', $dto->name);
        $this->assertEquals('email', $dto->email);

        $recipient = new Recipient('name', null, new PostalAddress('a', 'b', 'c'));
        $dto = $recipient->toDto();
        $this->assertEquals('name', $dto->name);
        $this->assertEquals('a', $dto->line1);
        $this->assertEquals('b', $dto->town);
        $this->assertEquals('c', $dto->postcode);
    }
}
