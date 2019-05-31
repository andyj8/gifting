<?php

namespace Gifting\Domain\Person;

use Gifting\Dto\SenderDto;
use InvalidArgumentException;

class Sender
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @param string $name
     * @param string $email
     * @param string $orderId
     */
    public function __construct($name, $email, $orderId)
    {
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('Sender name required');
        }
        if (empty($email) || !is_string($email)) {
            throw new InvalidArgumentException('Sender email required');
        }
        if (empty($orderId)) {
            throw new InvalidArgumentException('Order ID required');
        }

        $this->name = $name;
        $this->email = $email;
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return SenderDto
     */
    public function toDto()
    {
        $dto = new SenderDto();
        $dto->name = $this->name;
        $dto->email = $this->email;
        $dto->order_id = $this->orderId;

        return $dto;
    }
}
