<?php
require_once(dirname(__FILE__).'/../lib/bootstrap_integration.php');

class PensioLoginTest extends MockitTestCase
{
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantApi;
	
	public function setup()
	{
		$this->merchantApi = new PensioMerchantAPI(PENSIO_INTEGRATION_INSTALLATION, PENSIO_INTEGRATION_USERNAME, PENSIO_INTEGRATION_PASSWORD);
	}
	
	public function testSuccessfullLogin()
	{
		$response = $this->merchantApi->login();
		
		$this->assertTrue($response->wasSuccessful());
	}
}