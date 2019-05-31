<?php
namespace Gifting\Test\Behat\ServiceLevel;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Gifting\Domain\Gift\Redemption\Redemption;
use Gifting\Dto;
use Gifting\Application\UseCase\Command;
use Gifting\Domain\Gift\Gift;
use Gifting\Domain\Gift\GiftId;
use Gifting\Domain\Gift\GiftSpecification;
use Gifting\Domain\Person\PostalAddress;
use Gifting\Domain\Person\Recipient;
use Gifting\Domain\Person\Sender;
use Gifting\Domain\Product\Product;
use Gifting\Domain\Voucher\Voucher;
use Gifting\Application\Container\ApplicationContainer as Container;
use Gifting\Infrastructure\Delivery\InMemoryGiftPostbox;
use Gifting\Infrastructure\Delivery\Transport\HealthyTransport;
use Gifting\Infrastructure\Email\TestEmailClient;
use Gifting\Infrastructure\Persistence\InMemoryDeliveryRepository;
use Gifting\Infrastructure\Persistence\InMemoryGiftRepository;

class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \Exception
     */
    private $lastException;

    /**
     * @var mixed
     */
    private $lastResult;

    public function __construct()
    {
        date_default_timezone_set('Europe/London');

        $this->container = new Container();

        $this->container['delivery.transport.email'] = new HealthyTransport();
        $this->container['delivery.transport.tribeka'] = new HealthyTransport();
        $this->container['mandril.client'] = new TestEmailClient();
        $this->container['gift.repository'] = new InMemoryGiftRepository();
        $this->container['delivery.repository'] = new InMemoryDeliveryRepository();
        $this->container['delivery.postbox'] = new InMemoryGiftPostbox(
            $this->container['delivery.service']
        );
    }

    /**
     * @BeforeScenario
     */
    public function setup()
    {
        $this->container['gift.repository']->clear();
    }

    /**
     * @Given the current time is :hours hour prior to the same-day delivery cutoff
     */
    public function theCurrentTimeIsHourPriorToTheSameDayDeliveryCutoff($hours)
    {
        $cutoff = \DateTime::createFromFormat('Hi', $this->container['config']['delivery']['same_day_cutoff']);

         $this->container['gift.factory.new']
            ->setNow($cutoff->sub(new \DateInterval('PT' . $hours . 'H')));
    }

    /**
     * @Given the current time is :hours hour after the same-day delivery cutoff
     */
    public function theCurrentTimeIsHourAfterTheSameDayDeliveryCutoff($hours)
    {
        $cutoff = \DateTime::createFromFormat('Hi', $this->container['config']['delivery']['same_day_cutoff']);

        $this->container['gift.factory.new']
            ->setNow($cutoff->add(new \DateInterval('PT' . $hours . 'H')));
    }

    /**
     * @When I send an eGift without delivery date
     */
    public function iSendAnEgiftWithoutDeliveryDate()
    {
        $command = new Command\CreateGiftCommand($this->getEGiftDto());
        $this->container['usecase.create_gift']->handle($command);
    }

    /**
     * @When I send an eGift for delivery in :days days from now
     */
    public function iSendAnEgiftForDeliveryInDaysFromNow($days)
    {
        $now = (new \DateTime())->add(new \DateInterval('P' . $days . 'D'));

        $giftDto = $this->getEGiftDto();
        $giftDto->specification->delivery_date = $now;

        $command = new Command\CreateGiftCommand($giftDto);
        $this->container['usecase.create_gift']->handle($command);
    }

    /**
     * @Then my gift should be saved
     */
    public function myGiftShouldBeSaved()
    {
        \PHPUnit_Framework_Assert::assertCount(1, $this->container['gift.repository']->getAll());
    }

    /**
     * @Then my gift should be delivered immediately
     */
    public function myGiftShouldBeDeliveredImmediately()
    {
        \PHPUnit_Framework_Assert::assertEquals(1, $this->container['delivery.transport.email']->getSentCount());
    }

    /**
     * @Then I should be notified it was delivered
     */
    public function iShouldBeNotifiedItWasDelivered()
    {
        \PHPUnit_Framework_Assert::assertEquals(1, $this->container['mandril.client']->getSentCount());
    }

    /**
     * @Then my gift should be scheduled for delivery in :days days from now
     */
    public function myGiftShouldBeScheduledForDeliveryInDaysFromNow($days)
    {
        $saved = $this->container['gift.repository']->getAll();

        \PHPUnit_Framework_Assert::assertEquals(
            (new \DateTime())->add(new \DateInterval('P' . $days . 'D'))->format('Y-m-d'),
            $saved[0]->getSpecification()->getDeliveryDate()->format('Y-m-d')
        );
    }

    /**
     * @Then my gift should be scheduled for delivery for tomorrow
     */
    public function myGiftShouldBeScheduledForDeliveryForTomorrow()
    {
        $saved = $this->container['gift.repository']->getAll();

        \PHPUnit_Framework_Assert::assertEquals(
            (new \DateTime())->add(new \DateInterval('P1D'))->format('Y-m-d'),
            $saved[0]->getSpecification()->getDeliveryDate()->format('Y-m-d')
        );
    }

    /**
     * @When I send an physical gift without delivery date
     */
    public function iSendAnPhysicalGiftWithoutDeliveryDate()
    {
        $command = new Command\CreateGiftCommand($this->getPhysicalGiftDto());
        $this->container['usecase.create_gift']->handle($command);
    }

    /**
     * @Given I have a gift ready for redemption
     */
    public function iHaveAGiftReadyForRedemption()
    {
        $giftType = GiftSpecification::TYPE_PHYSICAL;
        $expiry = (new \DateTime('now'))->add(new \DateInterval('P5D'));

        $gift = new Gift(
            new GiftId('gift-1'),
            new Sender('Alice', 'email', 'order-1'),
            new Recipient('Bob', 'email', new PostalAddress('line1', 'town', 'postcode')),
            new Product('sku', 'name', 'type', 'imageurl'),
            new GiftSpecification($giftType, 'styleref', 'message', new \DateTime('now')),
            new Voucher('code', $expiry)
        );

        $this->container['gift.repository']->save($gift);
    }

    /**
     * @When I send my voucher code
     */
    public function iSendMyVoucherCode()
    {
        $command = new Command\RedeemGiftCommand('code', '1.2.3.4');

        try {
            $this->container['usecase.redeem_gift']->handle($command);
        } catch (\Exception $exception) {
            $this->lastException = $exception;
        }
    }

    /**
     * @Then my gift should be marked as redeemed
     */
    public function myGiftShouldBeMarkedAsRedeemed()
    {
        $saved = $this->container['gift.repository']->getAll();

        \PHPUnit_Framework_Assert::assertTrue($saved['gift-1']->isRedeemed());
    }

    /**
     * @Given my gift has already been redeemed
     */
    public function myGiftHasAlreadyBeenRedeemed()
    {
        $giftType = GiftSpecification::TYPE_PHYSICAL;
        $expiry = (new \DateTime('now'))->add(new \DateInterval('P5D'));

        $gift = new Gift(
            new GiftId('gift-1'),
            new Sender('Alice', 'email', 'order-1'),
            new Recipient('Bob', 'email', new PostalAddress('line1', 'town', 'postcode')),
            new Product('sku', 'name', 'type', 'imageurl'),
            new GiftSpecification($giftType, 'styleref', 'message', new \DateTime('now')),
            new Voucher('code', $expiry),
            new Redemption(new \DateTime(), '1.2.3.4')
        );

        $this->container['gift.repository']->save($gift);
    }

    /**
     * @Given my gift has expired
     */
    public function myGiftHasExpired()
    {
        $giftType = GiftSpecification::TYPE_PHYSICAL;
        $expiry = (new \DateTime('now'))->sub(new \DateInterval('P5D'));

        $gift = new Gift(
            new GiftId('gift-1'),
            new Sender('Alice', 'email', 'order-1'),
            new Recipient('Bob', 'email', new PostalAddress('line1', 'town', 'postcode')),
            new Product('sku', 'name', 'type', 'imageurl'),
            new GiftSpecification($giftType, 'styleref', 'message', new \DateTime('now')),
            new Voucher('code', $expiry)
        );

        $this->container['gift.repository']->save($gift);
    }

    /**
     * @Then I should receive a gift already redeemed error
     */
    public function iShouldReceiveAGiftAlreadyRedeemedError()
    {
        \PHPUnit_Framework_Assert::assertInstanceOf(
            '\Gifting\Domain\Gift\Exception\GiftAlreadyRedeemedException',
            $this->lastException
        );
    }

    /**
     * @Then I should receive a gift expired error
     */
    public function iShouldReceiveAGiftExpiredError()
    {
        \PHPUnit_Framework_Assert::assertInstanceOf(
            '\Gifting\Domain\Gift\Exception\GiftExpiredException',
            $this->lastException
        );
    }

    /**
     * @Then I should receive a gift not found error
     */
    public function iShouldReceiveAGiftNotFoundError()
    {
        \PHPUnit_Framework_Assert::assertInstanceOf(
            '\Gifting\Domain\Gift\Exception\GiftNotFoundException',
            $this->lastException
        );
    }

    /**
     * @When I retrieve the gift
     */
    public function iRetrieveTheGift()
    {
        $command = new Command\RetrieveGiftCommand('code');

        try {
            $this->lastResult = $this->container['usecase.retrieve_gift']->handle($command);
        } catch (\Exception $exception) {
            $this->lastException = $exception;
        }
    }

    /**
     * @Then I should receive the gift data
     */
    public function iShouldReceiveTheGiftData()
    {
        \PHPUnit_Framework_Assert::assertInstanceOf('\Gifting\Dto\GiftDto', $this->lastResult);
    }

    /**
     * @return Dto\GiftDto
     */
    private function getEGiftDto()
    {
        $json = json_decode('{
           "sender":{
              "name":"Admin User",
              "email":"admin@ebs.io",
              "order_id":300013
           },
           "recipient":{
              "name":"aa",
              "email":"ab",
              "line1":null,
              "town":null,
              "postcode":null
           },
           "product":{
              "sku":"9780007383979",
              "type":"book",
              "name":"The Girl in Times Square",
              "image_url":"9780007383979"
           },
           "specification":{
              "type":"egift",
              "style_ref":"gifting_xmas",
              "message":"test",
              "delivery_date":null
           }
        }');

        return (new Dto\GiftDto())
            ->setSender(new Dto\SenderDto($json->sender))
            ->setRecipient(new Dto\RecipientDto($json->recipient))
            ->setProduct(new Dto\ProductDto($json->product))
            ->setSpecification(new Dto\GiftSpecificationDto($json->specification));
    }

    /**
     * @return Dto\GiftDto
     */
    private function getPhysicalGiftDto()
    {
        $json = json_decode('{
           "sender":{
              "name":"Admin User",
              "email":"admin@ebs.io",
              "order_id":300013
           },
           "recipient":{
              "name":"aa",
              "email":null,
              "line1":"bb",
              "town":"cc",
              "postcode":"dd"
           },
           "product":{
              "sku":"9780007383979",
              "type":"book",
              "name":"The Girl in Times Square",
              "image_url":"9780007383979"
           },
           "specification":{
              "type":"physical",
              "style_ref":"gifting_xmas",
              "message":"test",
              "delivery_date":null
           }
        }');

        return (new Dto\GiftDto())
            ->setSender(new Dto\SenderDto($json->sender))
            ->setRecipient(new Dto\RecipientDto($json->recipient))
            ->setProduct(new Dto\ProductDto($json->product))
            ->setSpecification(new Dto\GiftSpecificationDto($json->specification));
    }
}
