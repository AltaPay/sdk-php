<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorCurlBasedHttpUtils_ValitorNetworkProblemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

	/**
	 * ArrayCachingLogger
	 */
	private $logger;

	/**
	 * @var ValitorMerchantAPI
	 */
	private $merchantApi;
	/**
	 * @var ValitorCurlBasedHttpUtils
	 */
	private $httpUtils;

    protected function setUp(): void
	{
		$this->logger = new ArrayCachingLogger();
		$this->httpUtils = new ValitorCurlBasedHttpUtils(5, 3, false);
	}

	/**
	 * @expectedException ValitorConnectionFailedException
	 */
	public function testConnectionRefused()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'http://localhost:28888/',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException ValitorConnectionFailedException
	 */
	public function testNoConnection()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'http://testgateway.valitor.com:28888/',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException ValitorRequestTimeoutException
	 */
	public function testRequestTimeout()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'https://testbank.valitor.com/Sleep?time=21&',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$this->merchantApi->login();
	}

	/**
	 * @expectedException ValitorInvalidResponseException
	 */
	public function testNonXMLResponse()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'https://testbank.valitor.com',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException ValitorUnauthorizedAccessException
	 */
	public function testUnauthorizedResponse()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'https://testgateway.valitor.com/',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException ValitorInvalidResponseException
	 */
	public function testNonHTTP200Response()
	{
		$this->merchantApi = new ValitorMerchantAPI(
			'http://www.valitor.com/',
			'username',
			'password',
			$this->logger,
			$this->httpUtils
		);
		$response = $this->merchantApi->login();
	}
}
