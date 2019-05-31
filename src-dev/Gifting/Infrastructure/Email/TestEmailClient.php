<?php

namespace Gifting\Infrastructure\Email;

use Email\MandrillClient;
use Email\MandrillResponse;
use Email\Message;

class TestEmailClient extends MandrillClient
{
    /**
     * @var integer
     */
    private $sentCount = 0;

    public function __construct() {}

    /**
     * Send an email message using a template.
     *
     * @param Message $message The message to send
     *
     * @return MandrillResponse
     */
    public function sendMessage(Message $message)
    {
        $this->sentCount++;

        $response = new \stdClass();
        $response->status = true;

        return new MandrillResponse($response);
    }

    /**
     * @return integer
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }
}
