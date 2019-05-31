<?php

namespace Gifting\Test\Domain\Gift;

use DateInterval;
use DateTime;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftSpecification;
use Gifting\Domain\Voucher\Voucher;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class GiftTest extends PHPUnit_Framework_TestCase
{
    public function testIsAwareOfSendToday()
    {
        $giftId     = m::mock('Gifting\Domain\Gift\GiftId');
        $sender     = m::mock('Gifting\Domain\Person\Sender');
        $recipient  = m::mock('Gifting\Domain\Person\Recipient');
        $product    = m::mock('Gifting\Domain\Product\Product');
        $voucher    = m::mock('Gifting\Domain\Voucher\Voucher');
        $redemption = m::mock('Gifting\Domain\Gift\Redemption\Redemption');

        $today = new DateTime();
        $giftSpec = new GiftSpecification('egift', 'style', 'message', $today);

        $gift = new Gift(
            $giftId,
            $sender,
            $recipient,
            $product,
            $giftSpec,
            $voucher,
            $redemption
        );

        $this->assertTrue($gift->shouldSendToday());

        $tomorrow = $today->add(new DateInterval('P1D'));
        $giftSpec = new GiftSpecification('egift', 'style', 'message', $tomorrow);

        $gift = new Gift(
            $giftId,
            $sender,
            $recipient,
            $product,
            $giftSpec,
            $voucher,
            $redemption
        );

        $this->assertFalse($gift->shouldSendToday());
    }

    public function testCannotRedeemIfAlreadyRedeemed()
    {
        $giftId     = m::mock('Gifting\Domain\Gift\GiftId');
        $sender     = m::mock('Gifting\Domain\Person\Sender');
        $recipient  = m::mock('Gifting\Domain\Person\Recipient');
        $product    = m::mock('Gifting\Domain\Product\Product');
        $giftSpec   = m::mock('Gifting\Domain\Gift\GiftSpecification');
        $voucher    = m::mock('Gifting\Domain\Voucher\Voucher');
        $redemption = m::mock('Gifting\Domain\Gift\Redemption\Redemption');

        $gift = new Gift(
            $giftId,
            $sender,
            $recipient,
            $product,
            $giftSpec,
            $voucher,
            $redemption
        );

        $this->setExpectedException('Gifting\Domain\Gift\Exception\GiftAlreadyRedeemedException');
        $gift->redeem('1.2.3.4');
    }

    public function testCannotRedeemIfExpired()
    {
        $giftId     = m::mock('Gifting\Domain\Gift\GiftId');
        $sender     = m::mock('Gifting\Domain\Person\Sender');
        $recipient  = m::mock('Gifting\Domain\Person\Recipient');
        $product    = m::mock('Gifting\Domain\Product\Product');
        $giftSpec   = m::mock('Gifting\Domain\Gift\GiftSpecification');

        $past = (new DateTime())->sub(new DateInterval('P5D'));
        $voucher = new Voucher('CODE', $past);

        $gift = new Gift(
            $giftId,
            $sender,
            $recipient,
            $product,
            $giftSpec,
            $voucher
        );

        $this->setExpectedException('Gifting\Domain\Gift\Exception\GiftExpiredException');
        $gift->redeem('1.2.3.4');
    }

    public function testCanRedeem()
    {
        $giftId     = m::mock('Gifting\Domain\Gift\GiftId');
        $sender     = m::mock('Gifting\Domain\Person\Sender');
        $recipient  = m::mock('Gifting\Domain\Person\Recipient');
        $product    = m::mock('Gifting\Domain\Product\Product');
        $giftSpec   = m::mock('Gifting\Domain\Gift\GiftSpecification');

        $past = (new DateTime())->add(new DateInterval('P5D'));
        $voucher = new Voucher('CODE', $past);

        $gift = new Gift(
            $giftId,
            $sender,
            $recipient,
            $product,
            $giftSpec,
            $voucher
        );

        $gift->redeem('1.2.3.4');
        $this->assertNotNull($gift->getRedemption());
    }
}
