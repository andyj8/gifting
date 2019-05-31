<?php

namespace Gifting\Application\Console;

use Gifting\Application\Container\ApplicationContainer;
use Symfony\Component\Console\Command\Command;

/*
 * Run worker that picks up gifts in postbox and delivers.
 *
 */
class RunPostboxWorker extends Command
{
    protected function configure()
    {
        $this
            ->setName('gifting:worker:postbox')
            ->setDescription('Run postbox worker');
    }

    protected function execute()
    {
        $container = new ApplicationContainer();

        $consumer = $container['rabbit.consumer.postbox'];
        $worker   = $container['worker.postbox'];

        $consumer->setWorker($worker);
        $consumer->run();
    }
}
