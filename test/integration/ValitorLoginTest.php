<?php

class ValitorLoginTest extends MockitTestCase
{
	/**
	 * @var ValitorMerchantAPI
	 */
	private $merchantApi;

    /**
     * @throws Exception
     */
    public function setup()
	{
		$this->merchantApi = new ValitorMerchantAPI(VALITOR_INTEGRATION_INSTALLATION, VALITOR_INTEGRATION_USERNAME, VALITOR_INTEGRATION_PASSWORD);
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorMerchantAPIException
     */
    public function testSuccessfullLogin()
	{
		$response = $this->merchantApi->login();

		$this->assertTrue($response->wasSuccessful());
	}
}
