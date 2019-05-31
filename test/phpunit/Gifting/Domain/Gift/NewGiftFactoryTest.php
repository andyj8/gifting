<?php

namespace Gifting\Test\Domain\Gift;

use DateInterval;
use DateTime;
use Gifting\Domain\Gift\NewGiftFactory;
use Gifting\Dto\GiftDto;
use Gifting\Dto\GiftSpecificationDto;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class NewGiftFactoryTest extends PHPUnit_Framework_TestCase
{
    const CUTOFF = 1500;

    public function testGetsFreshId()
    {
        $giftFactory = m::mock('Gifting\Domain\Gift\GiftFactory');
        $giftFactory->shouldReceive('create')->with(m::on(function(GiftDto $giftDto) {
            $this->assertEquals(100, $giftDto->id);
            return true;
        }));

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldReceive('nextIdentity')->andReturn(100);

        $giftDto = new GiftDto();
        $spec = new GiftSpecificationDto();
        $spec->delivery_date = new DateTime();
        $giftDto->specification = $spec;

        $factory = new NewGiftFactory($giftFactory, $giftRepository, self::CUTOFF);
        $factory->create($giftDto);
    }

    public function testUsesSpecifiedDeliveryDateIfAtLeastTomorrow()
    {
        $deliveryDate = (new DateTime())->add(new DateInterval('P5D'));;

        $giftFactory = m::mock('Gifting\Domain\Gift\GiftFactory');
        $giftFactory->shouldReceive('create')->with(m::on(function(GiftDto $giftDto) use ($deliveryDate) {
            $this->assertEquals($deliveryDate, $giftDto->specification->delivery_date);
            return true;
        }));

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldIgnoreMissing();

        $giftDto = new GiftDto();
        $spec = new GiftSpecificationDto();
        $spec->delivery_date = $deliveryDate;
        $giftDto->specification = $spec;

        $factory = new NewGiftFactory($giftFactory, $giftRepository, self::CUTOFF);
        $factory->create($giftDto);
    }

    public function testSetsTodayIfNoDeliveryDateSpecifiedAndWithinCutoff()
    {
        $giftFactory = m::mock('Gifting\Domain\Gift\GiftFactory');
        $giftFactory->shouldReceive('create')->with(m::on(function(GiftDto $giftDto) {
            $this->assertEquals((new DateTime())->format('Y-m-d'), $giftDto->specification->delivery_date->format('Y-m-d'));
            return true;
        }));

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldIgnoreMissing();

        $giftDto = new GiftDto();
        $spec = new GiftSpecificationDto();
        $giftDto->specification = $spec;

        $now = new DateTime();
        $now->setTime(9, 0);

        $factory = new NewGiftFactory($giftFactory, $giftRepository, self::CUTOFF, $now);
        $factory->create($giftDto);
    }

    public function testSetsTomorrowIfNoDeliveryDateSpecifiedAndOverCutoff()
    {
        $giftFactory = m::mock('Gifting\Domain\Gift\GiftFactory');
        $giftFactory->shouldReceive('create')->with(m::on(function(GiftDto $giftDto) {
            $tomorrow = (new DateTime())->add(new DateInterval('P1D'));
            $this->assertEquals($tomorrow->format('Y-m-d'), $giftDto->specification->delivery_date->format('Y-m-d'));
            return true;
        }));

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldIgnoreMissing();

        $giftDto = new GiftDto();
        $spec = new GiftSpecificationDto();
        $giftDto->specification = $spec;

        $factory = new NewGiftFactory($giftFactory, $giftRepository, self::CUTOFF);
        $factory->setNow((new DateTime())->setTime(21, 0));

        $factory->create($giftDto);
    }

    public function testSetsTomorrowDeliveryDateIfOverCutoff()
    {
        $giftFactory = m::mock('Gifting\Domain\Gift\GiftFactory');
        $giftFactory->shouldReceive('create')->with(m::on(function (GiftDto $giftDto) {
            $tomorrow = (new DateTime())->add(new DateInterval('P1D'));
            $this->assertEquals($tomorrow->format('Y-m-d'), $giftDto->specification->delivery_date->format('Y-m-d'));
            return true;
        }));

        $giftRepository = m::mock('Gifting\Domain\Gift\GiftRepository');
        $giftRepository->shouldIgnoreMissing();

        $giftDto = new GiftDto();
        $spec = new GiftSpecificationDto();
        $spec->delivery_date = new DateTime();
        $giftDto->specification = $spec;

        $factory = new NewGiftFactory($giftFactory, $giftRepository, self::CUTOFF);
        $factory->setNow((new DateTime())->setTime(21, 0));

        $factory->create($giftDto);
    }
}
