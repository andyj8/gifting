<?php

namespace Gifting\Application\Container;

use Gifting\Infrastructure\Messaging\BuzzAdminClient;
use Messaging\Administration\Administration;
use Messaging\Config\RabbitConfig;
use Messaging\Config\VhostConfig;
use Messaging\Connection;
use Messaging\Consumer;
use Messaging\Exchange;
use Messaging\Queue;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MessagingProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container An Container instance
     */
    public function register(Container $container)
    {
        $rabbitConfig = RabbitConfig::createFromArray($container['config']['rabbit']);
        $vhostConfig  = VhostConfig::createFromArray($container['config']['rabbit']['vhosts']['gifting']);

        $container['rabbit.administration.gifting'] = function () use ($rabbitConfig, $vhostConfig) {
            $administration = new Administration($rabbitConfig, $vhostConfig);
            $administration->setAdminClient(new BuzzAdminClient($rabbitConfig, $vhostConfig));

            return $administration;
        };

        $container['rabbit.connection.gifting'] = function ($container) use ($rabbitConfig, $vhostConfig) {
            $connection = new Connection($rabbitConfig, $vhostConfig);
            $connection->setAdministration($container['rabbit.administration.gifting']);

            return $connection;
        };

        $container['rabbit.exchange.postbox'] = function ($container) {
            return new Exchange(
                'gifting.postbox',
                $container['rabbit.connection.gifting'],
                $container['logger.deadletter']
            );
        };

        $container['rabbit.queue.postbox'] = function ($container) {
            return new Queue("gifting.postbox", $container['rabbit.connection.gifting']);
        };

        $container['rabbit.consumer.postbox'] = function ($container) {
            return new Consumer($container['rabbit.queue.postbox'], $container['logger.deadletter']);
        };
    }
}