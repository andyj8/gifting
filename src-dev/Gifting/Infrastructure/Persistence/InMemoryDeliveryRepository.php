<?php

namespace Gifting\Infrastructure\Persistence;

use Gifting\Domain\Delivery\DeliveryAttempt;
use Gifting\Domain\Delivery\DeliveryRepository;
use Gifting\Domain\Gift\Gift;

class InMemoryDeliveryRepository implements DeliveryRepository
{
    /**
     * @param Gift $gift
     *
     * @return DeliveryAttempt[]
     */
    public function findByGift(Gift $gift)
    {
        // TODO: Implement findByGift() method.
    }

    /**
     * @param DeliveryAttempt $deliveryAttempt
     */
    public function save(DeliveryAttempt $deliveryAttempt)
    {
        // TODO: Implement save() method.
    }
}
