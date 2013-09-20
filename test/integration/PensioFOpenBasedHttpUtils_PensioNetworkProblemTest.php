<?php
require_once(dirname(__FILE__).'/../lib/bootstrap_integration.php');

class PensioFOpenBasedHttpUtils_PensioNetworkProblemTest extends MockitTestCase
{
	/**
	 * @var ArrayCachingLogger
	 */
	private $logger;
	
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantApi;
	
	public function setup()
	{
		$this->logger = new ArrayCachingLogger();
		$this->httpUtils = new PensioFOpenBasedHttpUtils(5, 3);
	}
	
	/**
	 * @expectedException PensioConnectionFailedException
	 */
	public function testConnectionRefused()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://localhost:28888/'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}
	
	/**
	 * @expectedException PensioConnectionFailedException
	 */
	public function testNoConnection()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://testgateway.pensio.com:28888/'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$this->merchantApi->login();
	}
	
	/**
	 * @expectedException PensioRequestTimeoutException
	 * Disabled due to the unstable nature of the php fopen timeout code. DHAKA DHAKA DHAKA
	 */
	public function _testRequestTimeout()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'https://testbank.pensio.com/Sleep?time=21&'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		try
		{
			$this->merchantApi->login();
		}
		catch(Exception $exception)
		{
			if(!($exception instanceof PensioRequestTimeoutException))
			{
				print_r($this->logger->getLogs());
			}
			throw $exception;
		}
	}
	
	/**
	 * @expectedException PensioInvalidResponseException
	 */
	public function testNonXMLResponse()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'https://testbank.pensio.com'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$this->merchantApi->login();
	}

	/**
	 * @expectedException PensioUnauthorizedAccessException
	 */
	public function testUnauthorizedResponse()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'https://testgateway.pensio.com/'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException PensioInvalidResponseException
	 */
	public function testNonHTTP200Response()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://www.pensio.com/'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}
}