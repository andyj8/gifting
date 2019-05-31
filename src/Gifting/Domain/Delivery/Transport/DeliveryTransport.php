<?php

namespace Gifting\Domain\Delivery\Transport;

use Exception;
use Gifting\Domain\Gift\Gift;

interface DeliveryTransport
{
    /**
     * @param $gift
     *
     * @return boolean
     */
    public function supports(Gift $gift);

    /**
     * @param Gift $gift
     *
     * @return DeliveryDto
     *
     * @throws Exception
     */
    public function deliver(Gift $gift);
}
