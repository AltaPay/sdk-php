<?php
require_once(dirname(__FILE__) . '/../lib/bootstrap_integration.php');

class ValitorSubscriptionTest extends MockitTestCase
{
	/**
	 * @var ValitorMerchantAPI
	 */
	private $merchantApi;

    /**
     * @throws ValitorMerchantAPIException
     */
    public function setup()
	{
		$this->logger = new ArrayCachingLogger();
		$this->merchantApi = new ValitorMerchantAPI(
			VALITOR_INTEGRATION_INSTALLATION,
			VALITOR_INTEGRATION_USERNAME,
			VALITOR_INTEGRATION_PASSWORD,
			$this->logger
		);
		$this->merchantApi->login();
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testSuccessfullSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
			VALITOR_INTEGRATION_TERMINAL,
			'subscription' . time(),
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011110000',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$this->assertTrue($response->wasSuccessful(), $response->getMerchantErrorMessage());
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testDeclinedSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
			VALITOR_INTEGRATION_TERMINAL,
			'subscription-declined' . time(),
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011111466',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$this->assertTrue($response->wasDeclined(), $response->getMerchantErrorMessage());
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testErroneousSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
			VALITOR_INTEGRATION_TERMINAL,
			'subscription-error' . time(),
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011111467',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$this->assertTrue($response->wasErroneous(), $response->getMerchantErrorMessage());
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testSuccessfulChargeSubscription()
	{
		$subscriptionResponse = $this->merchantApi->setupSubscription(
			VALITOR_INTEGRATION_TERMINAL,
			'subscription-charge' . time(),
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011110000',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$chargeResponse = $this->merchantApi->chargeSubscription($subscriptionResponse->getPrimaryPayment()->getId());

		$this->assertTrue($chargeResponse->wasSuccessful(), $chargeResponse->getMerchantErrorMessage());
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testSuccessfulChargeSubscriptionWithToken()
	{
		$verifyCardResponse = $this->merchantApi->verifyCard(
			VALITOR_INTEGRATION_TERMINAL,
			'verify-card' . time(),
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011110000',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$subscriptionResponseWithToken = $this->merchantApi->setupSubscriptionWithToken(
			VALITOR_INTEGRATION_TERMINAL,
			'subscription-with-token' . time(),
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			$verifyCardResponse->getPrimaryPayment()->getCreditCardToken(),
			'123',
			'eCommerce'
		);

		$this->assertTrue($subscriptionResponseWithToken->wasSuccessful(), $subscriptionResponseWithToken->getMerchantErrorMessage());
	}
}
