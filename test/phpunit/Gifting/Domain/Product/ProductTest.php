<?php

namespace Gifting\Test\Domain\Product;

use Gifting\Domain\Product\Product;
use PHPUnit_Framework_TestCase;

class ProductTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresSku()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Product(null, 'value', 'value', 'value');
    }

    public function testRequiresName()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Product('value', null, 'value', 'value');
    }

    public function testRequiresType()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Product('value', 'value', null, 'value');
    }

    public function testRequiresImage()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Product('value', 'value', 'value', null);
    }
}
