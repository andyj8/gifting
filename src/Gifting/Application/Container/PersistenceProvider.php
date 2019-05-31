<?php

namespace Gifting\Application\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PersistenceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container An Container instance
     */
    public function register(Container $container)
    {
        $container['dbal'] = function ($container) {
            $config = new \Doctrine\DBAL\Configuration();
            return \Doctrine\DBAL\DriverManager::getConnection($container['config']['db'], $config);
        };
    }
}
