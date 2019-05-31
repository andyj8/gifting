<?php

namespace Gifting\Test\Domain\Gift;

use DateTime;
use Gifting\Domain\Gift\GiftSpecification;
use PHPUnit_Framework_TestCase;

class GiftSpecificationTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresValidType()
    {
        $this->setExpectedException('InvalidArgumentException');

        new GiftSpecification('BADTYPE', 'ref', 'msg', new DateTime());
    }

    public function testRequiresValidStyleRef()
    {
        $this->setExpectedException('InvalidArgumentException');

        $type = GiftSpecification::TYPE_EGIFT;
        new GiftSpecification($type, null, 'msg', new DateTime());
        new GiftSpecification($type, 1234, 'msg', new DateTime());
    }

    public function testRequiresValidMessage()
    {
        $this->setExpectedException('InvalidArgumentException');

        $type = GiftSpecification::TYPE_EGIFT;
        new GiftSpecification($type, 'ref', null, new DateTime());
        new GiftSpecification($type, 'ref', 1234, new DateTime());
    }

    public function testConvertsToDto()
    {
        $type = GiftSpecification::TYPE_EGIFT;
        $date = new DateTime();
        $spec = new GiftSpecification($type, 'ref', 'msg', $date);

        $dto = $spec->toDto();
        $this->assertEquals($type, $dto->type);
        $this->assertEquals('ref', $dto->style_ref);
        $this->assertEquals('msg', $dto->message);
        $this->assertEquals($date, $dto->delivery_date);
    }
}
