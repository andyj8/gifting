<?php

use Gifting\Application\UseCase\Command;
use Gifting\Dto;

require __DIR__ . '/../vendor/autoload.php';

$container = new \Gifting\Application\Container\ApplicationContainer();

$app = new \Slim\Slim(['debug' => false]);
$app->response()->headers->set('Content-Type', 'application/json');

$app->error(function (\Exception $e) use ($app) {
    if ($e instanceof \InvalidArgumentException) {
        $app->response()->setStatus($e->getCode());
    }
    $app->response()->headers->set('Content-Type', 'text/plain');
    $app->response()->setBody($e->getMessage());
});

/**
 * Retrieve a gift.
 */
$app->get('/gift/:voucherCode', function($voucherCode) use ($app, $container) {
    $command = new Command\RetrieveGiftCommand($voucherCode);
    $result = $container['usecase.retrieve_gift']->handle($command);

    if (!$result) {
        $app->response()->status(404);
        $app->stop();
    }

    $app->response()->setBody(json_encode($result));
});

/**
 * Create a gift.
 */
$app->post('/gift', function () use ($app, $container) {
    $json = json_decode($app->request->getBody());

    if (json_last_error() !== JSON_ERROR_NONE) {
        $app->response()->status(400);
        $app->stop();
    }
    if (empty($json->sender)) {
        $app->response()->status(422);
        $app->stop();
    }
    if (empty($json->recipient)) {
        $app->response()->status(422);
        $app->stop();
    }
    if (empty($json->product)) {
        $app->response()->status(422);
        $app->stop();
    }
    if (empty($json->specification)) {
        $app->response()->status(422);
        $app->stop();
    }

    $giftDto = (new Dto\GiftDto())
        ->setSender(new Dto\SenderDto($json->sender))
        ->setRecipient(new Dto\RecipientDto($json->recipient))
        ->setProduct(new Dto\ProductDto($json->product))
        ->setSpecification(new Dto\GiftSpecificationDto($json->specification));

    $command = new Command\CreateGiftCommand($giftDto);
    $gift = $container['usecase.create_gift']->handle($command);

    if (!$gift) {
        $app->response()->status(500);
        $app->stop();
    }

    $app->response()->status(201);
    $app->response()->setBody(json_encode($gift));
});

/**
 * Redeem a gift.
 */
$app->post('/redeem', function () use ($app, $container) {
    $json = json_decode($app->request->getBody());

    if (json_last_error() !== JSON_ERROR_NONE) {
        $app->response()->status(400);
        $app->stop();
    }
    if (empty($json->voucher_code)) {
        $app->response()->status(422);
        $app->stop();
    }
    if (empty($json->client_ip)) {
        $app->response()->status(422);
        $app->stop();
    }

    $command = new Command\RedeemGiftCommand($json->voucher_code, $json->client_ip);
    $redeemedSku = $container['usecase.redeem_gift']->handle($command);

    $app->response()->setBody($redeemedSku);
});

$app->run();
