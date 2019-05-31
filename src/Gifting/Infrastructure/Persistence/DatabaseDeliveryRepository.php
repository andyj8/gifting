<?php

namespace Gifting\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Gifting\Domain\Delivery\DeliveryAttempt;
use Gifting\Domain\Delivery\DeliveryRepository;
use Gifting\Domain\Gift\Gift;

class DatabaseDeliveryRepository implements DeliveryRepository
{
    /**
     * @var Connection
     */
    private $dbh;

    /**
     * @param Connection $dbh
     */
    public function __construct(Connection $dbh)
    {
        $this->dbh = $dbh;
    }

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
        $this->dbh->insert('deliveries', [
            'gift_id'   => $deliveryAttempt->getGift()->getId()->asString(),
            'attempted' => $deliveryAttempt->getAttempted()->format('Y-m-d H:i:s'),
            'request'   => $deliveryAttempt->getRequest(),
            'response'  => $deliveryAttempt->getResponse(),
            'success'   => $deliveryAttempt->wasSuccessful() ? 't' : 'f'
        ]);
    }
}
