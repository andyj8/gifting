<?php

namespace Gifting\Application\UseCase;

use Gifting\Application\UseCase\Command\CreateGiftCommand;
use Gifting\Domain\Delivery\GiftPostbox;
use Gifting\Domain\Gift\GiftRepository;
use Gifting\Domain\Gift\NewGiftFactory;
use Gifting\Dto\GiftDto;

class CreateGift
{
    /**
     * @var NewGiftFactory
     */
    private $giftFactory;

    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @var GiftPostbox
     */
    private $giftPostbox;

    /**
     * @param NewGiftFactory $giftFactory
     * @param GiftRepository $giftRepository
     * @param GiftPostbox $giftPostbox
     */
    public function __construct(
        NewGiftFactory $giftFactory,
        GiftRepository $giftRepository,
        GiftPostbox $giftPostbox
    ) {
        $this->giftFactory = $giftFactory;
        $this->giftRepository = $giftRepository;
        $this->giftPostbox = $giftPostbox;
    }

    /**
     * @param CreateGiftCommand $command
     *
     * @return GiftDto
     */
    public function handle(CreateGiftCommand $command)
    {
        $gift = $this->giftFactory->create($command->getGiftDto());

        $this->giftRepository->save($gift);

        if ($gift->shouldSendToday()) {
            $this->giftPostbox->post($gift);
        }

        return $gift->toDto();
    }
}
