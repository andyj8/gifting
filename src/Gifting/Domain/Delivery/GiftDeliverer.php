<?php

namespace Gifting\Domain\Delivery;

use Gifting\Domain\Delivery\Event\GiftDeliveredEvent;
use Gifting\Domain\Delivery\Exception\GiftDeliveryFailedException;
use Gifting\Domain\Delivery\Transport\TransportFactory;
use Gifting\Domain\Event\EventDispatcher;
use Gifting\Domain\Gift\Gift;

class GiftDeliverer
{
    /**
     * @var TransportFactory
     */
    private $transportFactory;

    /**
     * @var DeliveryRepository
     */
    private $deliveryRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param TransportFactory $transportFactory
     * @param DeliveryRepository $deliveryRepository
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        TransportFactory $transportFactory,
        DeliveryRepository $deliveryRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->transportFactory = $transportFactory;
        $this->deliveryRepository = $deliveryRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Gift $gift
     *
     * @throws GiftDeliveryFailedException
     */
    public function deliver(Gift $gift)
    {
        $transport = $this->transportFactory->getTransportFor($gift);
        $result = $transport->deliver($gift);

        $deliveryAttemptFactory = new DeliveryAttemptFactory();
        $deliveryAttempt = $deliveryAttemptFactory->createDeliveryAttempt($gift, $result);
        $this->deliveryRepository->save($deliveryAttempt);

        if (!$deliveryAttempt->succeeded()) {
            throw new GiftDeliveryFailedException('Failed sending gift');
        }

        $this->eventDispatcher->dispatch(new GiftDeliveredEvent($gift));
    }
}
