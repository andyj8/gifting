<?php

namespace Gifting\Application\EventListener;

use Gifting\Domain\Delivery\Event\GiftDeliveredEvent;
use Gifting\Domain\Gift\Gift;
use Email\MandrillClient;
use Email\Message;
use Psr\Log\LoggerInterface as Logger;

class GiftDeliveredListener
{
    /**
     * @var MandrillClient
     */
    private $client;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param MandrillClient $client
     * @param Logger $logger
     */
    public function __construct(MandrillClient $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param GiftDeliveredEvent $event
     */
    public function onGiftDelivered(GiftDeliveredEvent $event)
    {
        $id = $event->getGift()->getId()->asString();

        $this->logger->debug('Gift ' . $id . ' delivered');

        $message = $this->createMessage($event->getGift());
        $result = $this->client->sendMessage($message);

        if ($result->getDidSendSucceed()) {
            $this->logger->debug('Delivery confirmation sent for gift ' . $id);
        } else {
            $this->logger->error('Delivery confirmation failed to send for gift ' . $id);
        }
    }

    /**
     * @param Gift $gift
     *
     * @return Message
     */
    private function createMessage(Gift $gift)
    {
        return new Message([
            'recipients' => [$gift->getSender()->getEmail()],
            'subject'    => 'Your Gift to ' . $gift->getRecipient()->getName() . ' Has Been Delivered',
            'from'       => 'no-reply@sainsburysentertainment.co.uk',
            'template'   => 'gifting_delivered',
            'variables'  => [
                'sender_name' => $gift->getSender()->getName(),
                'book_title'  => $gift->getProduct()->getName()
            ]
        ]);
    }
}
