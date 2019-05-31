<?php

namespace Gifting\Domain\Delivery\Transport;

use Gifting\Domain\Delivery\Exception\NoSuitableTransportException;
use Gifting\Domain\Gift\Gift;

class TransportFactory
{
    /**
     * @var DeliveryTransport[]
     */
    private $transports;

    /**
     * @param DeliveryTransport[] $transports
     */
    public function __construct(array $transports)
    {
        $this->transports = $transports;
    }

    /**
     * @param Gift $gift
     *
     * @return DeliveryTransport
     *
     * @throws NoSuitableTransportException
     */
    public function getTransportFor(Gift $gift)
    {
        foreach ($this->transports as $transport) {
            if ($transport->supports($gift)) {
                return $transport;
            }
        }

        throw new NoSuitableTransportException('No valid transport');
    }
}
