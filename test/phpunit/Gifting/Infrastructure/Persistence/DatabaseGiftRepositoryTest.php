<?php

namespace Gifting\Test\Infrastructure\Persistence;

require_once 'DatabaseTestCase.php';

use DateTime;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftFactory;
use Gifting\Domain\Gift\GiftId;
use Gifting\Domain\Gift\GiftRepository;
use Gifting\Domain\Gift\GiftSpecification;
use Gifting\Domain\Gift\Redemption\Redemption;
use Gifting\Domain\Person\Recipient;
use Gifting\Domain\Person\Sender;
use Gifting\Domain\Product\Product;
use Gifting\Domain\Voucher\Voucher;
use Gifting\Infrastructure\Persistence\DatabaseGiftRepository;
use Mockery as m;

class DatabaseGiftRepositoryTest extends DatabaseTestCase
{
    /**
     * @var GiftRepository
     */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $voucherFactory = m::mock('Gifting\Domain\Voucher\VoucherFactory');
        $voucherFactory->shouldReceive('createVoucher')->andReturn(new Voucher('AAAA-AAAA-AAAA', new DateTime()));

        $giftFactory = new GiftFactory($voucherFactory);

        $this->repository = new DatabaseGiftRepository($this->dbh, $giftFactory);
    }

    public function testCanGetById()
    {
        $gift = $this->repository->getById(1);
        $this->assertEquals('1', $gift->getId()->asString());
    }

    public function testCanGetByVoucherCode()
    {
        $gift = $this->repository->getByVoucherCode('AAAA-AAAA-AAAA');
        $this->assertEquals('AAAA-AAAA-AAAA', $gift->getVoucher()->getCode());
    }

    public function testCanGetDueForDelivery()
    {
        $gifts = $this->repository->findDueForDeliveryOn(new DateTime('2020-01-01'));
        $this->assertEquals(1, count($gifts));
        $this->assertEquals('3', $gifts[0]->getId()->asString());
    }

    public function testCanSaveNewGift()
    {
        $insert = new Gift(
            new GiftId(1030),
            new Sender('senderName', 'senderEmail', 'orderId'),
            new Recipient('recipientName', 'recipientEmail'),
            new Product('productSku', 'productName', 'productType', 'imageUrl'),
            new GiftSpecification('egift', 'styleRef', 'message', new DateTime('2020-01-01')),
            new Voucher('ZZZ', new DateTime('2050-01-01'))
        );

        $this->repository->save($insert);

        $gift = $this->repository->getById(1030);

        $this->assertEquals('senderName', $gift->getSender()->getName());
        $this->assertEquals('senderEmail', $gift->getSender()->getEmail());
        $this->assertEquals('orderId', $gift->getSender()->getOrderId());

        $this->assertEquals('recipientName', $gift->getRecipient()->getName());
        $this->assertEquals('recipientEmail', $gift->getRecipient()->getEmail());

        $this->assertEquals('productSku', $gift->getProduct()->getSku());
        $this->assertEquals('productName', $gift->getProduct()->getName());
        $this->assertEquals('productType', $gift->getProduct()->getType());
        $this->assertEquals('imageUrl', $gift->getProduct()->getImageUrl());

        $this->assertEquals('egift', $gift->getSpecification()->getType());
        $this->assertEquals('styleRef', $gift->getSpecification()->getStyleRef());
        $this->assertEquals('message', $gift->getSpecification()->getMessage());
        $this->assertEquals('2020-01-01', $gift->getSpecification()->getDeliveryDate()->format('Y-m-d'));

        $this->assertEquals('AAAA-AAAA-AAAA', $gift->getVoucher()->getCode());
    }

    public function testCanUpdateGift()
    {
        $insert = new Gift(
            new GiftId(1030),
            new Sender('senderName', 'senderEmail', 'orderId'),
            new Recipient('recipientName', 'recipientEmail'),
            new Product('productSku', 'productName', 'productType', 'imageUrl'),
            new GiftSpecification('egift', 'styleRef', 'message', new DateTime('2020-01-01')),
            new Voucher('ZZZ', new DateTime('2050-01-01'))
        );

        $this->repository->save($insert);

        $update =  new Gift(
            new GiftId(1030),
            new Sender('revised', 'senderEmail', 'orderId'),
            new Recipient('recipientName', 'recipientEmail'),
            new Product('productSku', 'productName', 'productType', 'imageUrl'),
            new GiftSpecification('egift', 'styleRef', 'message', new DateTime('2020-01-01')),
            new Voucher('ZZZ', new DateTime('2050-01-01'))
        );

        $this->repository->save($update);

        $gift = $this->repository->getById(1030);

        $this->assertEquals('revised', $gift->getSender()->getName());
    }

    public function testCanSaveRedemption()
    {
        $insert = new Gift(
            new GiftId(1030),
            new Sender('senderName', 'senderEmail', 'orderId'),
            new Recipient('recipientName', 'recipientEmail'),
            new Product('productSku', 'productName', 'productType', 'imageUrl'),
            new GiftSpecification('egift', 'styleRef', 'message', new DateTime('2020-01-01')),
            new Voucher('ZZZ', new DateTime('2050-01-01')),
            new Redemption(new DateTime(), '1.2.3.4')
        );

        $this->repository->save($insert);

        $gift = $this->repository->getById(1030);

        $this->assertTrue($gift->isRedeemed());
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
