<?php

namespace Gifting\Infrastructure\Delivery\Transport;

use Exception;
use Gifting\Domain\Delivery\Transport\DeliveryDto;
use Gifting\Domain\Delivery\Transport\DeliveryTransport;
use Gifting\Domain\Gift\Gift;

class HealthyTransport implements DeliveryTransport
{
    /**
     * @var integer
     */
    private $sentCount = 0;

    /**
     * @param $gift
     *
     * @return boolean
     */
    public function supports(Gift $gift)
    {
        return true;
    }

    /**
     * @param Gift $gift
     *
     * @return DeliveryDto
     *
     * @throws Exception
     */
    public function deliver(Gift $gift)
    {
        $this->sentCount++;

        $result = new DeliveryDto();
        $result->request = 'test';
        $result->response = 'ok';
        $result->success = true;

        return $result;
    }

    /**
     * @return integer
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }
}
