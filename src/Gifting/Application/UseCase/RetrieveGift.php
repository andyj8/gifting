<?php

namespace Gifting\Application\UseCase;

use Gifting\Application\UseCase\Command\RetrieveGiftCommand;
use Gifting\Domain\Gift\GiftRepository;
use Gifting\Dto\GiftDto;

class RetrieveGift
{
    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @param GiftRepository $giftRepository
     */
    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    /**
     * @param RetrieveGiftCommand $command
     *
     * @return GiftDto|null
     */
    public function handle(RetrieveGiftCommand $command)
    {
        $gift = $this->giftRepository->getByVoucherCode($command->getVoucherCode());

        if (!$gift) {
            return null;
        }

        $gift->validateRedeemable();

        return $gift->toDto();
    }
}
