<?php

namespace Gifting\Application\UseCase;

use Gifting\Application\UseCase\Command\RedeemGiftCommand;
use Gifting\Domain\Gift\Redemption\GiftRedeemer;

class RedeemGift
{
    /**
     * @var GiftRedeemer
     */
    private $giftRedeemer;

    /**
     * @param GiftRedeemer $giftRedeemer
     */
    public function __construct(GiftRedeemer $giftRedeemer)
    {
        $this->giftRedeemer = $giftRedeemer;
    }

    /**
     * @param RedeemGiftCommand $command
     *
     * @return string
     */
    public function handle(RedeemGiftCommand $command)
    {
        $gift = $this->giftRedeemer->redeem(
            $command->getVoucherCode(),
            $command->getClientIp()
        );

        return $gift->getProduct()->getSku();
    }
}
