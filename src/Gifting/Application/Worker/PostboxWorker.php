<?php

namespace Gifting\Application\Worker;

use Exception;
use Gifting\Domain\Delivery\GiftDeliverer;
use Gifting\Domain\Gift\GiftRepository;
use Messaging\Message;
use Messaging\Queue;
use Messaging\Worker;
use Psr\Log\LoggerInterface as Logger;

class PostboxWorker implements Worker
{
    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @var GiftDeliverer
     */
    private $giftDeliverer;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param GiftRepository $giftRepository
     * @param GiftDeliverer $giftDeliverer
     * @param Logger $logger
     */
    public function __construct(
        GiftRepository $giftRepository,
        GiftDeliverer $giftDeliverer,
        Logger $logger
    ) {
        $this->giftRepository = $giftRepository;
        $this->giftDeliverer = $giftDeliverer;
        $this->logger = $logger;
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    public function processMessage(Message $message)
    {
        $payload = $message->getPayload();

        try {
            $gift = $this->giftRepository->getById($payload['id']);
            $this->giftDeliverer->deliver($gift);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return Queue::MESSAGE_DEAD;
        }

        return Queue::MESSAGE_ACK;
    }
}
