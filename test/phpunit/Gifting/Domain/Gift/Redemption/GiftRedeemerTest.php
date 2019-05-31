<?php

namespace Gifting\Test\Domain\Gift\Redemption;

use Gifting\Domain\Gift\Redemption\GiftRedeemer;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class GiftRedeemerTest extends PHPUnit_Framework_TestCase
{
    public function testFailsOnGiftNotFound()
    {
        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldReceive('getByVoucherCode')->andReturnNull();

        $eventDispatcher = m::mock('Gifting\Domain\Event\EventDispatcher');

        $redeemer = new GiftRedeemer($giftRepository, $eventDispatcher);

        $this->setExpectedException('Exception');
        $redeemer->redeem('code', '1.2.3.4');
    }

    public function testRedeems()
    {
        $gift = m::mock('Gifting\Domain\Gift\Gift');
        $gift->shouldReceive('redeem')->with('1.2.3.4');

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldReceive('getByVoucherCode')->andReturn($gift);
        $giftRepository->shouldReceive('save')->with($gift);

        $eventDispatcher = m::mock('Gifting\Domain\Event\EventDispatcher');
        $eventDispatcher->shouldReceive('dispatch');

        $redeemer = new GiftRedeemer($giftRepository, $eventDispatcher);
        $redeemer->redeem('code', '1.2.3.4');
    }
}
