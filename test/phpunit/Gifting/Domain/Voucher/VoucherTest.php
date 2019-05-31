<?php

namespace Gifting\Test\Domain\Voucher;

use DateInterval;
use DateTime;
use Gifting\Domain\Voucher\Voucher;
use PHPUnit_Framework_TestCase;

class VoucherTest extends PHPUnit_Framework_TestCase
{
    public function testRequiresValidCode()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Voucher(null, new DateTime());
        new Voucher('', new DateTime());
        new Voucher(1234, new DateTime());

        $this->setExpectedException(null);
        new Voucher('CODE', new DateTime());
    }

    public function testAwareIfExpired()
    {
        $future = (new DateTime())->add(new DateInterval('P5D'));
        $voucher = new Voucher('CODE', $future);
        $this->assertFalse($voucher->hasExpired());

        $past = (new DateTime())->sub(new DateInterval('P5D'));
        $voucher = new Voucher('CODE', $past);
        $this->assertTrue($voucher->hasExpired());
    }

    public function testConvertsToDto()
    {
        $expires = (new DateTime())->add(new DateInterval('P5D'));
        $voucher = new Voucher('CODE', $expires);
        $voucherDto = $voucher->toDto();

        $this->assertEquals('CODE', $voucherDto->code);
        $this->assertEquals($expires, $voucherDto->expiry);
    }
}
