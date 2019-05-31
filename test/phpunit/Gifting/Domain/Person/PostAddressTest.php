<?php

namespace Gifting\Test\Domain\Person;

use Gifting\Domain\Person\PostalAddress;
use PHPUnit_Framework_TestCase;

class PostAddressTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresLine1()
    {
        $this->setExpectedException('InvalidArgumentException');

        new PostalAddress(null, 'value', 'value');
    }

    public function testRequiresTown()
    {
        $this->setExpectedException('InvalidArgumentException');

        new PostalAddress('value', null, 'value');
    }

    public function testRequiresPostcode()
    {
        $this->setExpectedException('InvalidArgumentException');

        new PostalAddress('value', 'value', null);
    }
}
