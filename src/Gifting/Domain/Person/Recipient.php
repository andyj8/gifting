<?php

namespace Gifting\Domain\Person;

use Gifting\Dto\RecipientDto;
use InvalidArgumentException;

class Recipient
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
     * @var PostalAddress
     */
    private $postalAddress;

    /**
     * @param string $name
     * @param string $email
     * @param PostalAddress $postalAddress
     */
    public function __construct($name, $email = null, PostalAddress $postalAddress = null)
    {
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('Recipient name required');
        }
        if (empty($email) && empty($postalAddress)) {
            throw new InvalidArgumentException('Recipient address required');
        }

        $this->name = $name;
        $this->email = $email;
        $this->postalAddress = $postalAddress;
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
     * @return PostalAddress
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * @param RecipientDto $dto
     *
     * @return Recipient
     */
    public static function fromDto(RecipientDto $dto)
    {
        $postalAddress = null;
        if ($dto->line1) {
            $postalAddress = new PostalAddress(
                $dto->line1, $dto->town, $dto->postcode
            );
        }

        return new self($dto->name, $dto->email, $postalAddress);
    }

    /**
     * @return RecipientDto
     */
    public function toDto()
    {
        $dto = new RecipientDto();
        $dto->name = $this->name;

        if ($this->email) {
            $dto->email = $this->email;
        }

        if ($this->postalAddress) {
            $dto->line1 = $this->postalAddress->getLine1();
            $dto->town = $this->postalAddress->getTown();
            $dto->postcode = $this->postalAddress->getPostcode();
        }

        return $dto;
    }
}
