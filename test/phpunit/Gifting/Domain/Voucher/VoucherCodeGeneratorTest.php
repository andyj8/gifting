<?php

namespace Gifting\Test\Domain\Voucher;

use Gifting\Domain\Voucher\VoucherCodeConfig;
use Gifting\Domain\Voucher\VoucherCodeGenerator;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class VoucherCodeGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testFailsIfUniqueCodeNotAvailable()
    {
        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldReceive('getByVoucherCode')->andReturn(true);

        $prefixMap = [
            "magazine"     => "MA",
            "subscription" => "MS"
        ];

        $config = new VoucherCodeConfig('??-??', $prefixMap, 'AB');

        $generator = new VoucherCodeGenerator($config, $giftRepository);

        $this->setExpectedException('Exception');
        $generator->createUniqueCode('magazine');
    }

    public function testCreatesVoucherCode()
    {
        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldReceive('getByVoucherCode')->andReturnNull();

        $prefixMap = [
            "magazine"     => "MA",
            "subscription" => "MS"
        ];

        $config = new VoucherCodeConfig('??-??', $prefixMap, 'AB');

        $generator = new VoucherCodeGenerator($config, $giftRepository);
        $code = $generator->createUniqueCode('magazine');

        $this->assertRegExp('/^(MA)[AB][AB]-[AB][AB]$/', $code);
    }
}
