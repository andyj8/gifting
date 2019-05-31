<?php

namespace Gifting\Infrastructure\Delivery\Transport;

use Gifting\Domain\Delivery\Transport\DeliveryDto;
use Gifting\Domain\Delivery\Transport\DeliveryTransport;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftSpecification;
use Email\MandrillClient;
use Email\Message;

class EmailTransport implements DeliveryTransport
{
    /**
     * @var MandrillClient
     */
    private $client;

    /**
     * @param MandrillClient $client
     */
    public function __construct(MandrillClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $gift
     *
     * @return boolean
     */
    public function supports(Gift $gift)
    {
        return $gift->getSpecification()->getType() === GiftSpecification::TYPE_EGIFT;
    }

    /**
     * @param Gift $gift
     *
     * @return DeliveryDto
     */
    public function deliver(Gift $gift)
    {
        $message = new Message([
            'recipients' => [$gift->getRecipient()->getEmail()],
            'subject'    => 'A Gift',
            'from'       => 'no-reply@sainsburysentertainment.co.uk',
            'template'   => $gift->getSpecification()->getStyleRef(),
            'variables'  => [
                'sender_name' => $gift->getSender()->getName(),
                'message'     => $gift->getSpecification()->getMessage(),
                'voucher'     => $gift->getVoucher()->getCode(),
                'book_title'  => $gift->getProduct()->getName(),
                'book_cover'  => $gift->getProduct()->getImageUrl()
            ]
        ]);

        $response = $this->client->sendMessage($message);

        $result = new DeliveryDto();
        $result->request  = json_encode($message->getVariables());
        $result->response = json_encode($response->getRawResponse());
        $result->success  = $response->getDidSendSucceed();

        return $result;
    }
}
