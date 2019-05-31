<?php

namespace Gifting\Domain\Delivery;

use DateTime;
use Gifting\Domain\Delivery\Transport\DeliveryDto;
use Gifting\Domain\Gift\Gift;

class DeliveryAttemptFactory
{
    /**
     * @param Gift $gift
     * @param DeliveryDto $result
     *
     * @return DeliveryAttempt
     */
    public function createDeliveryAttempt(Gift $gift, DeliveryDto $result)
    {
        return new DeliveryAttempt(
            new DateTime(),
            $gift,
            $result->request,
            $result->response,
            $result->success
        );
    }
}
