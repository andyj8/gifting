<?php

namespace Gifting\Domain\Delivery;

use DateTime;
use Gifting\Domain\Gift\Gift;
use InvalidArgumentException;

class DeliveryAttempt
{
    /**
     * @var DateTime
     */
    private $attempted;

    /**
     * @var Gift
     */
    private $gift;

    /**
     * @var string
     */
    private $request;

    /**
     * @var string
     */
    private $response;

    /**
     * @var boolean
     */
    private $success;

    /**
     * @param DateTime $attempted
     * @param Gift $gift
     * @param string $request
     * @param string $response
     * @param boolean $success
     */
    public function __construct(DateTime $attempted, Gift $gift, $request, $response, $success)
    {
        if (empty($request) || !is_string($request)) {
            throw new InvalidArgumentException('Request required');
        }
        if (empty($response) || !is_string($response)) {
            throw new InvalidArgumentException('Response required');
        }
        if (!is_bool($success)) {
            throw new InvalidArgumentException('Outcome required');
        }

        $this->attempted = $attempted;
        $this->gift = $gift;
        $this->request = $request;
        $this->response = $response;
        $this->success = $success;
    }

    /**
     * @return DateTime
     */
    public function getAttempted()
    {
        return $this->attempted;
    }

    /**
     * @return Gift
     */
    public function getGift()
    {
        return $this->gift;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return boolean
     */
    public function wasSuccessful()
    {
        return $this->success;
    }

    /**
     * @return boolean
     */
    public function succeeded()
    {
        return $this->success === true;
    }
}
