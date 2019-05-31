<?php

namespace Gifting\Infrastructure\Delivery\Transport;

use Exception;
use Gifting\Domain\Delivery\Transport\DeliveryDto;
use Gifting\Domain\Delivery\Transport\DeliveryTransport;
use Gifting\Domain\Gift\Gift;

class UnwellTransport implements DeliveryTransport
{
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
        $result = new DeliveryDto();
        $result->request = 'test';
        $result->response = 'fail';
        $result->success = false;

        return $result;
    }
}
