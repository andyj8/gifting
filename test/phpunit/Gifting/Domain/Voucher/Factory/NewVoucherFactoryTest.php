<?php

namespace Gifting\Test\Domain\Voucher\Factory;

use DateInterval;
use DateTime;
use Gifting\Domain\Voucher\Factory\NewVoucherFactory;
use Gifting\Dto\GiftDto;
use Gifting\Dto\ProductDto;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class NewVoucherFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreatesWithDeterminedLifetime()
    {
        $codeGenerator = m::mock('Gifting\Domain\Voucher\VoucherCodeGenerator');
        $codeGenerator->shouldReceive('createUniqueCode')->andReturn('CODE');

        $factory = new NewVoucherFactory(100, $codeGenerator);

        $giftDto = new GiftDto();
        $productDto = new ProductDto();
        $productDto->type = 'magazine';
        $giftDto->product = $productDto;

        $voucher = $factory->createVoucher($giftDto);

        $expectedExpiry = (new DateTime())->add(new DateInterval('P100D'));
        $this->assertEquals($expectedExpiry->format('Y-m-d'), $voucher->getExpiry()->format('Y-m-d'));
    }
}
