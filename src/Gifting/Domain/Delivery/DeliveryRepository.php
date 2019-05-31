<?php

namespace Gifting\Domain\Delivery;

use Gifting\Domain\Gift\Gift;

interface DeliveryRepository
{
    /**
     * @param Gift $gift
     *
     * @return DeliveryAttempt[]
     */
    public function findByGift(Gift $gift);

    /**
     * @param DeliveryAttempt $deliveryAttempt
     */
    public function save(DeliveryAttempt $deliveryAttempt);
}
