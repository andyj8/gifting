<?php

namespace Gifting\Domain\Gift;

use DateTime;
use Gifting\Dto\GiftSpecificationDto;
use InvalidArgumentException;

class GiftSpecification
{
    const TYPE_EGIFT = 'egift';
    const TYPE_PHYSICAL = 'physical';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $styleRef;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTime
     */
    private $deliveryDate;

    /**
     * @param string $type
     * @param string $styleRef
     * @param string $message
     * @param DateTime $deliveryDate
     */
    public function __construct($type, $styleRef, $message, DateTime $deliveryDate)
    {
        $validTypes = [
            self::TYPE_EGIFT,
            self::TYPE_PHYSICAL
        ];

        if (!in_array($type, $validTypes)) {
            throw new InvalidArgumentException('Invalid gift type');
        }

        if (empty($styleRef) || !is_string($styleRef)) {
            throw new InvalidArgumentException('Invalid style reference');
        }

        if (empty($message) || !is_string($message)) {
            throw new InvalidArgumentException('Invalid message');
        }

        $this->type = $type;
        $this->styleRef = $styleRef;
        $this->message = $message;
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStyleRef()
    {
        return $this->styleRef;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param GiftSpecificationDto $specDto
     *
     * @return GiftSpecification
     */
    public static function fromDto(GiftSpecificationDto $specDto)
    {
        return new self(
            $specDto->type,
            $specDto->style_ref,
            $specDto->message,
            $specDto->delivery_date
        );
    }

    /**
     * @return GiftSpecificationDto
     */
    public function toDto()
    {
        $dto = new GiftSpecificationDto();

        $dto->type = $this->type;
        $dto->style_ref = $this->styleRef;
        $dto->message = $this->message;
        $dto->delivery_date = $this->deliveryDate;

        return $dto;
    }
}
