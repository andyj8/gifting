<?php

namespace Gifting\Application\Container;

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoggingProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container An Container instance
     */
    public function register(Container $container)
    {
        $container['logger.formatter.logstash'] = function () {
            return new LogstashFormatter('slapi');
        };

        $container['logger.formatter.plain'] = function () {
            $output = "[%datetime%] %channel%.%level_name%: %message%\n %context%\n %extra%\n\n";
            return new LineFormatter($output);
        };

        $dir = $container['config']['logger']['api_logdir'];
        $container['logger.api'] = $this->createLogger('api', $dir);

        $dir = $container['config']['logger']['worker_logdir'];
        $container['logger.worker'] = $this->createLogger('worker', $dir);

        $dir = $container['config']['logger']['deadletter_logdir'];
        $container['logger.deadletter'] = $this->createLogger('deadletter', $dir);
    }

    /**
     * @param string $name
     * @param string $dir
     *
     * @return \Closure
     */
    private function createLogger($name, $dir)
    {
        return function (Container $container) use ($name, $dir) {
            $logger = new Logger($name);

            if ($container['config']['dev_mode']) {
                $path = $dir . '/error.plain.log';
                $errorLog = new StreamHandler($path, Logger::ERROR, false);
                $errorLog->setFormatter($container['logger.formatter.plain']);
                $logger->pushHandler($errorLog);
            } else {
                $path = $dir . '/error.log';
                $errorLog = new StreamHandler($path, Logger::ERROR, false);
                $errorLog->setFormatter($container['logger.formatter.logstash']);
                $logger->pushHandler($errorLog);
            }

            if ($container['config']['logger']['debug_logging']) {
                if ($container['config']['dev_mode']) {
                    $path = $dir . '/debug.plain.log';
                    $defaultLog = new StreamHandler($path, Logger::DEBUG);
                    $defaultLog->setFormatter($container['logger.formatter.plain']);
                    $logger->pushHandler($defaultLog);
                } else {
                    $path = $dir . '/debug.log';
                    $defaultLog = new StreamHandler($path, Logger::DEBUG);
                    $defaultLog->setFormatter($container['logger.formatter.logstash']);
                    $logger->pushHandler($defaultLog);
                }
            }

            return $logger;
        };
    }
}
