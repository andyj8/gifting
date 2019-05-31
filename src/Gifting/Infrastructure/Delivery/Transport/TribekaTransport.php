<?php

namespace Gifting\Infrastructure\Delivery\Transport;

use Buzz\Client\FileGetContents;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Gifting\Domain\Delivery\Transport\DeliveryDto;
use Gifting\Domain\Delivery\Transport\DeliveryTransport;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftSpecification;

class TribekaTransport implements DeliveryTransport
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $resource;

    /**
     * @param string $host
     * @param string $resource
     */
    public function __construct($host, $resource)
    {
        $this->host = $host;
        $this->resource = $resource;
    }

    /**
     * @param $gift
     *
     * @return boolean
     */
    public function supports(Gift $gift)
    {
        return $gift->getSpecification()->getType() === GiftSpecification::TYPE_PHYSICAL;
    }

    /**
     * @param Gift $gift
     *
     * @return DeliveryDto
     */
    public function deliver(Gift $gift)
    {
        $address = $gift->getRecipient()->getPostalAddress();

        $params = [
            'OrderNumber'      => $gift->getSender()->getOrderId(),
            'CardDesign'       => $gift->getSpecification()->getStyleRef(),
            'ImageUri'         => $gift->getProduct()->getImageUrl(),
            'SkuDescription'   => $gift->getProduct()->getName(),
            'RedemptionCode'   => $gift->getVoucher()->getCode(),
            'RedemptionExpiry' => $gift->getVoucher()->getExpiry()->format('d/m/Y'),
            'GiftMessage'      => $gift->getSpecification()->getMessage(),
            'Name'             => $gift->getRecipient()->getName(),
            'AddressLine1'     => $address->getLine1(),
            'City'             => $address->getTown(),
            'Postcode'         => $address->getPostcode()
        ];

        $request = new Request('POST', $this->resource, $this->host);
        $request->addHeader('Content-Type: application/json');
        $request->setContent(json_encode($params));

        $response = new Response();
        $client = new FileGetContents();
        $client->send($request, $response);

        $result = new DeliveryDto();
        $result->request  = json_encode($params);
        $result->response = json_encode($response->getContent());
        $result->success  = $response->isOk();

        return $result;
    }
}
