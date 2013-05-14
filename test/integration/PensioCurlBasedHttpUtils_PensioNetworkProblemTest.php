<?php
require_once(dirname(__FILE__).'/../lib/bootstrap_integration.php');

class PensioCurlBasedHttpUtils_PensioNetworkProblemTest extends MockitTestCase
{
	/**
	 * ArrayCachingLogger
	 */
	private $logger;
	
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantApi;
	
	public function setup()
	{
		$this->logger = new ArrayCachingLogger();
		$this->httpUtils = new PensioCurlBasedHttpUtils(15, 5);
	}
	
	/**
	 * @expectedException ConnectionFailedException
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
	 * @expectedException ConnectionFailedException
	 */
	public function testNoConnection()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://testgateway.pensio.com:28888/'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}
	
	/**
	 * @expectedException RequestTimeoutException
	 */
	public function testRequestTimeout()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://testbank.pensio.com/Sleep?time=120&'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}
	
	/**
	 * @expectedException InvalidResponseException
	 */
	public function testNonXMLResponse()
	{
		$this->merchantApi = new PensioMerchantAPI(
				'http://testbank.pensio.com'
				, 'username'
				, 'password'
				, $this->logger
				, $this->httpUtils);
		$response = $this->merchantApi->login();
	}

	/**
	 * @expectedException UnauthorizedAccessException
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
	 * @expectedException InvalidResponseException
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