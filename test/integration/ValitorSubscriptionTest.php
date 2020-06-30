<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorSubscriptionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var TestConfig */
    private $config;
    /** @var ValitorMerchantAPI */
    private $merchantApi;
    /** @var ArrayCachingLogger */
    private $logger;

    /**
     * @throws ValitorMerchantAPIException
     */
    protected function setUp(): void
    {
        $this->config = new TestConfig();
        $this->logger = new ArrayCachingLogger();
        $this->merchantApi = new ValitorMerchantAPI(
            $this->config->installation,
            $this->config->username,
            $this->config->password,
            $this->logger
        );
        $this->merchantApi->login();
    }

    public function testSuccessfullSetupSubscription(): void
    {
        $response = $this->merchantApi->setupSubscription(
            $this->config->terminal,
            'subscription'.time(),
            42.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        static::assertTrue($response->wasSuccessful(), $response->getMerchantErrorMessage());
    }

    public function testDeclinedSetupSubscription(): void
    {
        $response = $this->merchantApi->setupSubscription(
            $this->config->terminal,
            'subscription-declined'.time(),
            42.00,
            $this->config->currency,
            '4111000011111466',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        static::assertTrue($response->wasDeclined(), $response->getMerchantErrorMessage());
    }

    public function testErroneousSetupSubscription(): void
    {
        $response = $this->merchantApi->setupSubscription(
            $this->config->terminal,
            'subscription-error'.time(),
            42.00,
            $this->config->currency,
            '4111000011111467',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        static::assertTrue($response->wasErroneous(), $response->getMerchantErrorMessage());
    }

    public function testSuccessfulChargeSubscription(): void
    {
        $subscriptionResponse = $this->merchantApi->setupSubscription(
            $this->config->terminal,
            'subscription-charge'.time(),
            42.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );
        $payment = $subscriptionResponse->getPrimaryPayment();
        static::assertNotNull($payment);

        $chargeResponse = $this->merchantApi->chargeSubscription($payment->getId());

        static::assertTrue($chargeResponse->wasSuccessful(), $chargeResponse->getMerchantErrorMessage());
    }

    public function testSuccessfulChargeSubscriptionWithToken(): void
    {
        $verifyCardResponse = $this->merchantApi->verifyCard(
            $this->config->terminal,
            'verify-card'.time(),
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        $payment = $verifyCardResponse->getPrimaryPayment();
        static::assertNotNull($payment);

        $subscriptionResponseWithToken = $this->merchantApi->setupSubscriptionWithToken(
            $this->config->terminal,
            'subscription-with-token'.time(),
            42.00,
            $this->config->currency,
            $payment->getCreditCardToken(),
            '123',
            'eCommerce'
        );

        static::assertTrue($subscriptionResponseWithToken->wasSuccessful(), $subscriptionResponseWithToken->getMerchantErrorMessage());
    }
}
