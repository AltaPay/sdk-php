<?php
require_once(dirname(__FILE__) . '/../lib/bootstrap_integration.php');

class ValitorRefundTest extends MockitTestCase
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
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testReservationCaptureRefund()
	{
		$testReconciliationIdentifier = "reconrecon";
		$testAllowOverRefunding = true;
		$testInvoiceNumber = "invoiceinvoice";
		$testOrderId = "SomeOrderId";
		$testAmount = 42.24;
		$testSalesTax = 0.0;

		$testOrderLines = array(
			array('description' => 'SomeDescription', 'itemId' => 'KungFuBoy', 'quantity' => 1.00, 'unitPrice' => 21.12, 'taxAmount' => 0.00, 'unitCode' => 'kg', 'discount' => 0.00, 'goodsType' => 'item'),
			array('description' => 'SomeDescription', 'itemId' => 'KarateKid', 'quantity' => 1.00, 'unitPrice' => 21.12, 'taxAmount' => 0.00, 'unitCode' => 'kg', 'discount' => 0.00, 'goodsType' => 'item')
		);

		$response = $this->merchantApi->reservationOfFixedAmount(
			VALITOR_INTEGRATION_TERMINAL,
			$testOrderId,
			$testAmount,
			VALITOR_INTEGRATION_CURRENCY,
			'4111000011110000',
			'2020',
			'12',
			'123',
			'eCommerce'
		);

		$this->assertTrue($response->wasSuccessful());

		$response = $this->merchantApi->captureReservation(
			$response->getPrimaryPayment()->getId(),
			$testAmount,
			$testOrderLines,
			$testSalesTax,
			$testReconciliationIdentifier,
			$testInvoiceNumber
		);

		$this->assertTrue($response->wasSuccessful());

		$response = $this->merchantApi->refundCapturedReservation(
			$response->getPrimaryPayment()->getId(),
			$testAmount,
			$testOrderLines,
			$testReconciliationIdentifier,
			$testAllowOverRefunding,
			$testInvoiceNumber
		);

		$this->assertTrue($response->wasSuccessful());
	}
}
