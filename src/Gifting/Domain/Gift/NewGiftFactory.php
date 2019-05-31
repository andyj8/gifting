<?php

namespace Gifting\Domain\Gift;

use DateInterval;
use DateTime;
use Gifting\Dto\GiftDto;

class NewGiftFactory
{
    /**
     * @var GiftFactory
     */
    private $giftFactory;

    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @var integer
     */
    private $sameDayCutoff;

    /**
     * @var DateTime
     */
    private $now;

    /**
     * @param GiftFactory $giftFactory
     * @param GiftRepository $giftRepository
     * @param integer $sameDayCutoff
     */
    public function __construct(
        GiftFactory $giftFactory,
        GiftRepository $giftRepository,
        $sameDayCutoff
    ) {
        $this->giftFactory = $giftFactory;
        $this->giftRepository = $giftRepository;
        $this->sameDayCutoff = $sameDayCutoff;
    }

    /**
     * @param GiftDto $giftDto
     *
     * @return Gift
     */
    public function create(GiftDto $giftDto)
    {
        $giftDto->id = $this->giftRepository->nextIdentity();

        if ($giftDto->specification->type === GiftSpecification::TYPE_EGIFT) {
            return $this->giftFactory->create($giftDto);
        }

        $deliveryDate = $giftDto->specification->delivery_date;
        if ($deliveryDate === null || ($deliveryDate->format('Ymd') <= $this->getNow()->format('Ymd'))) {
            $this->overrideDeliveryDate($giftDto);
        }

        return $this->giftFactory->create($giftDto);
    }

    /**
     * @param GiftDto $giftDto
     */
    private function overrideDeliveryDate(GiftDto $giftDto)
    {
        $now = $this->getNow();

        if ($now->format('Hi') > $this->sameDayCutoff) {
            $giftDto->specification->delivery_date = $now->add(new DateInterval('P1D'));
        } else {
            $giftDto->specification->delivery_date = $now;
        }
    }

    /**
     * @return DateTime
     */
    private function getNow()
    {
        return ($this->now) ? $this->now : new DateTime();
    }

    /**
     * @param DateTime $now
     */
    public function setNow(DateTime $now)
    {
        $this->now = $now;
    }
}
