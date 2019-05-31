<?php

namespace Gifting\Application\Container;

use Email\MandrillClient;
use Mandrill;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EmailProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container An Container instance
     */
    public function register(Container $container)
    {
        $container['vendor.email'] = function ($container) {
            $apiKey = $container['config']['mail']['mailchimp']['api_key'];
            $proxy  = $container['config']['mail']['proxy'];

            return new Mandrill($apiKey, $proxy);
        };

        $container['mandril.client'] = function ($container) {
            $useTestMail = $container['config']['mail']['use_test_account'];
            $testAccount = $container['config']['mail']['test_account'];

            return new MandrillClient($container['vendor.email'], $testAccount, $useTestMail);
        };
    }
}
