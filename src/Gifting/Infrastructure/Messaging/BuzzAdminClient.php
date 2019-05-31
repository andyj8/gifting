<?php

namespace Gifting\Infrastructure\Messaging;

use Buzz\Client\FileGetContents;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Messaging\Administration\AdminClient;
use Messaging\Config\RabbitConfig;
use Messaging\Config\VhostConfig;

class BuzzAdminClient implements AdminClient
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $authHeader;

    /**
     * @param RabbitConfig $rabbitConfig
     * @param VhostConfig $vhostConfig
     */
    public function __construct(RabbitConfig $rabbitConfig, VhostConfig $vhostConfig)
    {
        $this->host = sprintf('http://%s:%d/', $rabbitConfig->getHost(), $rabbitConfig->getAdminPort());
        $userPass = base64_encode("{$vhostConfig->getUsername()}:{$vhostConfig->getPassword()}");
        $this->authHeader = 'Authorization: Basic ' . $userPass;
    }

    /**
     * Get a list of accessible vhosts
     *
     * @return mixed
     */
    public function listVHosts()
    {
        $response = $this->send('/api/vhost');
    }

    /**
     * Get a list of queues
     *
     * @return mixed
     */
    public function listQueues()
    {
        $response = $this->send('/api/queues');
    }

    /**
     * Get a list of exchanges
     *
     * @return mixed
     */
    public function listExchanges()
    {
        $response = $this->send('/api/exchanges');
    }

    /**
     * @param $resource
     * @return Response
     */
    private function send($resource)
    {
        $request = new Request('GET', $resource, $this->host);
        $request->addHeader($this->authHeader);

        $response = new Response();
        $client = new FileGetContents();
        $client->send($request, $response);

        return $response;
    }
}
