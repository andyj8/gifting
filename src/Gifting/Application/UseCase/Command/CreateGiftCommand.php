<?php

namespace Gifting\Application\UseCase\Command;

use Gifting\Dto\GiftDto;

class CreateGiftCommand
{
    /**
     * @var GiftDto
     */
    private $giftDto;

    /**
     * @param GiftDto $giftDto
     */
    public function __construct(GiftDto $giftDto)
    {
        $this->giftDto = $giftDto;
    }

    /**
     * @return GiftDto
     */
    public function getGiftDto()
    {
        return $this->giftDto;
    }
}
