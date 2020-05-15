<?php
require_once(dirname(__FILE__) . '/../lib/bootstrap_integration.php');

class ValitorCreatePaymentRequestTest extends MockitTestCase
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
		$this->merchantApi = new ValitorMerchantAPI(VALITOR_INTEGRATION_INSTALLATION, VALITOR_INTEGRATION_USERNAME, VALITOR_INTEGRATION_PASSWORD);
		$this->merchantApi->login();
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testCreatePaymentRequest()
	{
	    $customerInfo = array(
            'type' => 'private',
            'billing_firstname' => 'John',
            'billing_lastname' => 'Doe',
            'billing_address' => 'Street 21 Copenhagen',
            'billing_postal' => '1000',
            'billing_city' => 'Copenhagen',
            'billing_region' => 'Denmark',
            'billing_country' => 'Denmark',
            'email' => 'JohnDoe@example.com',
            'customer_phone' => '20123456',
            'shipping_firstname' => 'John',
            'shipping_lastname' => 'Doe',
            'shipping_address' => 'Street 21 Copenhagen',
            'shipping_postal' => '1000',
            'shipping_city' => 'Copenhagen',
            'shipping_region' => 'Denmark',
            'shipping_country' => 'Denmark',
        );
		$response = $this->merchantApi->createPaymentRequest(
			VALITOR_INTEGRATION_TERMINAL,
			'testorder',
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'payment',
            $customerInfo

		);

		$this->assertTrue($response->wasSuccessful());
	}

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testCreatePaymentRequestWithMoreData()
	{
		$customerInfo = array(
			'billing_postal' => '2860',
			'billing_country' => 'DK', // 2 character ISO-3166
			'billing_address' => 'Rosenkæret 13',
			'billing_city' => 'Søborg',
			'billing_region' => null,
			'billing_firstname' => 'Jens',
			'billing_lastname' => 'Lyn',
			'email' => 'testperson@mydomain.com',
			'shipping_postal' => '2860',
			'shipping_country' => 'DK', // 2 character ISO-3166
			'shipping_address' => 'Rosenkæret 17',
			'shipping_city' => 'Søborg',
			'shipping_region' => null,
			'shipping_firstname' => 'Snej',
			'shipping_lastname' => 'Nyl',
		); // See the documentation for further details
		$cookie = 'some cookie';
		$language = 'da';
		$config = array(
			'callback_form' => 'http://127.0.0.1/valitorpayment/form.php', 'callback_ok' => 'http://127.0.0.1/valitorpayment/ok.php', 'callback_fail' => 'http://127.0.0.1/valitorpayment/fail.php', 'callback_redirect' => ''     // See documentation if this is needed
			, 'callback_open' => ''         // See documentation if this is needed
			, 'callback_notification' => '' // See documentation if this is needed
		);
		$transaction_info = array('auxkey' => 'aux data'); // this can be left out.

		$response = $this->merchantApi->createPaymentRequest(
			VALITOR_INTEGRATION_TERMINAL,
			'testorder',
			42.00,
			VALITOR_INTEGRATION_CURRENCY,
			'payment',
			$customerInfo,
			$cookie,
			$language,
			$config,
			$transaction_info
		);

		$this->assertTrue($response->wasSuccessful(), $response->getErrorMessage());
	}
}