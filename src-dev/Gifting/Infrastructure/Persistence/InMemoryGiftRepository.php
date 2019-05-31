<?php

namespace Gifting\Infrastructure\Persistence;

use DateTime;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftRepository;

class InMemoryGiftRepository implements GiftRepository
{
    /**
     * @var integer
     */
    private $nextId = 0;

    /**
     * @var Gift[]
     */
    private $gifts = [];

    /**
     * @param mixed $id
     *
     * @return Gift
     */
    public function getById($id)
    {
        if (isset($this->gifts[$id])) {
            return $this->gifts[$id];
        }

        return null;
    }

    /**
     * @param string $voucherCode
     *
     * @return Gift
     */
    public function getByVoucherCode($voucherCode)
    {
        foreach ($this->gifts as $gift) {
            if ($gift->getVoucher()->getCode() === $voucherCode) {
                return $gift;
            }
        }

        return null;
    }

    /**
     * @param DateTime $when
     *
     * @return Gift[]
     */
    public function findDueForDeliveryOn(DateTime $when)
    {
        foreach ($this->gifts as $gift) {
            if ($gift->getSpecification()->getDeliveryDate()->format('Y-m-d') === $when->format('Y-m-d')) {
                return $gift;
            }
        }

        return null;
    }

    /**
     * @return integer
     */
    public function nextIdentity()
    {
        return $this->nextId++;
    }

    /**
     * @param Gift $gift
     */
    public function save(Gift $gift)
    {
        $this->gifts[$gift->getId()->asString()] = $gift;
    }

    public function clear()
    {
        $this->gifts = [];
    }

    /**
     * @return \Gifting\Domain\Gift\Gift[]
     */
    public function getAll()
    {
        return $this->gifts;
    }
}
