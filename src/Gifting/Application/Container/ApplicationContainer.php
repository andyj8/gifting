<?php

namespace Gifting\Application\Container;

use Gifting\Application\EventListener\GiftDeliveredListener;
use Gifting\Application\UseCase;
use Gifting\Application\Worker\PostboxWorker;
use Gifting\Domain\Delivery\Event\GiftDeliveredEvent;
use Gifting\Domain\Delivery\GiftDeliverer;
use Gifting\Domain\Delivery\Transport\TransportFactory;
use Gifting\Domain\Gift\GiftFactory;
use Gifting\Domain\Gift\NewGiftFactory;
use Gifting\Domain\Gift\Redemption\GiftRedeemer;
use Gifting\Domain\Voucher\Factory\ExistingVoucherFactory;
use Gifting\Domain\Voucher\Factory\NewVoucherFactory;
use Gifting\Domain\Voucher\VoucherCodeConfig;
use Gifting\Domain\Voucher\VoucherCodeGenerator;
use Gifting\Infrastructure\Delivery\RabbitGiftPostbox;
use Gifting\Infrastructure\Event\SymfonyEventDispatcher;
use Gifting\Infrastructure\Delivery\Transport;
use Gifting\Infrastructure\Persistence;
use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ApplicationContainer extends Container
{
    public function __construct()
    {
        parent::__construct();

        $this['config'] = function () {
            return require __DIR__ . '/../../../../config/config.php';
        };

        $this->register(new LoggingProvider());
        $this->register(new MessagingProvider());
        $this->register(new PersistenceProvider());
        $this->register(new EmailProvider());

        $this->registerEvents();
        $this->registerServices();
        $this->registerDelivery();
        $this->registerUseCases();
        $this->registerFactories();
        $this->registerRepositories();
        $this->registerWorkers();
    }

    private function registerEvents()
    {
        $this['event.dispatcher'] = function() {
            $eventDispatcher = new EventDispatcher();

            $listener = new GiftDeliveredListener($this['mandril.client'], $this['logger.api']);
            $eventDispatcher->addListener(GiftDeliveredEvent::NAME, [$listener, 'onGiftDelivered']);

            return new SymfonyEventDispatcher($eventDispatcher);
        };
    }

    private function registerUseCases()
    {
        $this['usecase.create_gift'] = function () {
            return new UseCase\CreateGift(
                $this['gift.factory.new'],
                $this['gift.repository'],
                $this['delivery.postbox']
            );
        };

        $this['usecase.retrieve_gift'] = function () {
            return new UseCase\RetrieveGift($this['gift.repository']);
        };

        $this['usecase.redeem_gift'] = function () {
            return new UseCase\RedeemGift($this['redeem.service']);
        };
    }

    private function registerFactories()
    {
        $this['gift.factory'] = function () {
            return new GiftFactory($this['voucher.existing.factory']);
        };

        $this['gift.factory.new'] = function () {
            return new NewGiftFactory(
                new GiftFactory($this['voucher.new.factory']),
                $this['gift.repository'],
                $this['config']['delivery']['same_day_cutoff']
            );
        };

        $this['voucher.code.generator'] = function() {
            $config = new VoucherCodeConfig(
                $this['config']['voucher']['code']['format'],
                $this['config']['voucher']['code']['prefix_map'],
                $this['config']['voucher']['code']['avail_chars']
            );
            return new VoucherCodeGenerator($config, $this['gift.repository']);
        };

        $this['voucher.new.factory'] = function () {
            return new NewVoucherFactory(
                $this['config']['voucher']['lifetime'],
                $this['voucher.code.generator']
            );
        };

        $this['voucher.existing.factory'] = function() {
            return new ExistingVoucherFactory();
        };
    }

    private function registerDelivery()
    {
        $this['delivery.postbox'] = function () {
            return new RabbitGiftPostbox($this['rabbit.exchange.postbox']);
        };

        $this['delivery.transport.email'] = function () {
            return new Transport\EmailTransport(
                $this['mandril.client']
            );
        };

        $this['delivery.transport.tribeka'] = function () {
            return new Transport\TribekaTransport(
                $this['config']['tribeka']['host'],
                $this['config']['tribeka']['resource']
            );
        };

        $this['transport.factory'] = function () {
            return new TransportFactory([
                $this['delivery.transport.email'],
                $this['delivery.transport.tribeka']
            ]);
        };
    }

    private function registerRepositories()
    {
        $this['gift.repository'] = function () {
            return new Persistence\DatabaseGiftRepository(
                $this['dbal'],
                $this['gift.factory']
            );
        };

        $this['delivery.repository'] = function () {
            return new Persistence\DatabaseDeliveryRepository($this['dbal']);
        };
    }

    private function registerServices()
    {
        $this['delivery.service'] = function () {
            return new GiftDeliverer(
                $this['transport.factory'],
                $this['delivery.repository'],
                $this['event.dispatcher']
            );
        };

        $this['redeem.service'] = function () {
            return new GiftRedeemer(
                $this['gift.repository'],
                $this['event.dispatcher']
            );
        };
    }

    private function registerWorkers()
    {
        $this['worker.postbox'] = function () {
            return new PostboxWorker(
                $this['gift.repository'],
                $this['delivery.service'],
                $this['logger.worker']
            );
        };
    }
}
