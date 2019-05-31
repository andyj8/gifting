<?php

namespace Gifting\Domain\Gift;

use DateTime;

interface GiftRepository
{
    /**
     * @param mixed $id
     *
     * @return Gift
     */
    public function getById($id);

    /**
     * @param string $voucherCode
     *
     * @return Gift
     */
    public function getByVoucherCode($voucherCode);

    /**
     * @param DateTime $when
     *
     * @return Gift[]
     */
    public function findDueForDeliveryOn(DateTime $when);

    /**
     * @return integer
     */
    public function nextIdentity();

    /**
     * @param Gift $gift
     */
    public function save(Gift $gift);
}
