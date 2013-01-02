<?php
require_once(dirname(__FILE__).'/../lib/bootstrap_integration.php');

class PensioSubscriptionTests extends MockitTestCase
{
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantApi;
	
	public function setup()
	{
		$this->logger = new ArrayCachingLogger();
		$this->merchantApi = new PensioMerchantAPI(
				PENSIO_INTEGRATION_INSTALLATION
				, PENSIO_INTEGRATION_USERNAME
				, PENSIO_INTEGRATION_PASSWORD
				, $this->logger);
		$this->merchantApi->login();
	}
	
	public function testSuccessfullSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
				PENSIO_INTEGRATION_TERMINAL
				, 'subscription'.time()
				, 42.00
				, PENSIO_INTEGRATION_CURRENCY
				, '4111000011110000' 
				, '2020'
				, '12'
				, '123'
				, 'eCommerce');
		
		$this->assertTrue($response->wasSuccessful(), $response->getMerchantErrorMessage());
	}
	
	public function testDeclinedSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
				PENSIO_INTEGRATION_TERMINAL
				, 'subscription-declined'.time()
				, 42.00
				, PENSIO_INTEGRATION_CURRENCY
				, '4111000011111466'
				, '2020'
				, '12'
				, '123'
				, 'eCommerce');
	
		$this->assertTrue($response->wasDeclined(), $response->getMerchantErrorMessage());
	}
	
	public function testErroneousSetupSubscription()
	{
		$response = $this->merchantApi->setupSubscription(
				PENSIO_INTEGRATION_TERMINAL
				, 'subscription-error'.time()
				, 42.00
				, PENSIO_INTEGRATION_CURRENCY
				, '4111000011111467'
				, '2020'
				, '12'
				, '123'
				, 'eCommerce');
	
		$this->assertTrue($response->wasErroneous(), $response->getMerchantErrorMessage());
	}

	public function testSuccessfulChargeSubscription()
	{
		$subscriptionResponse = $this->merchantApi->setupSubscription(
				PENSIO_INTEGRATION_TERMINAL
				, 'subscription-charge'.time()
				, 42.00
				, PENSIO_INTEGRATION_CURRENCY
				, '4111000011110000'
				, '2020'
				, '12'
				, '123'
				, 'eCommerce');
	
		//$chargeResponse = $this->merchantApi->chargeSubscription($subscriptionResponse->getPrimaryPayment()->getId());
		
		print_r($this->logger->getLogs());
		print_r($chargeResponse);
		$this->assertTrue($chargeResponse->wasSuccessful(), $chargeResponse->getMerchantErrorMessage());
	}
	
}