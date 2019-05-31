<?php

namespace Gifting\Domain\Gift\Redemption;

use Gifting\Domain\Event\EventDispatcher;
use Gifting\Domain\Gift\Event\GiftRedeemedEvent;
use Gifting\Domain\Gift\Exception\GiftNotFoundException;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftRepository;

class GiftRedeemer
{
    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param GiftRepository $giftRepository
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        GiftRepository $giftRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->giftRepository = $giftRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $voucherCode
     * @param string $clientIp
     *
     * @return Gift
     *
     * @throws GiftNotFoundException
     */
    public function redeem($voucherCode, $clientIp)
    {
        $gift = $this->giftRepository->getByVoucherCode($voucherCode);

        if (!$gift) {
            throw new GiftNotFoundException();
        }

        $gift->redeem($clientIp);
        $this->giftRepository->save($gift);

        $this->eventDispatcher->dispatch(new GiftRedeemedEvent($gift));

        return $gift;
    }
}
