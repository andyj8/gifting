<?php

namespace Gifting\Application\Console;

use DateTime;
use Gifting\Application\Container\ApplicationContainer;
use Symfony\Component\Console\Command\Command;

/*
 * Publish gifts due for delivery today into the postbox queue.
 *
 */
class PublishGifts extends Command
{
    protected function configure()
    {
        $this
            ->setName('gifting:postgifts')
            ->setDescription('Put gifts due for delivery today into the postbox queue');
    }

    protected function execute()
    {
        $container = new ApplicationContainer();

        $postbox = $container['delivery.postbox.rabbit'];
        $giftRepository = $container['gift.repository'];

        $giftsToSend = $giftRepository->findDueForDeliveryOn(new DateTime());

        foreach ($giftsToSend as $gift) {
            $postbox->post($gift);
        }
    }
}
