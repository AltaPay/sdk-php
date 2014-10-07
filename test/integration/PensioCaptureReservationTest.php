<?php
require_once(dirname(__FILE__).'/../lib/bootstrap_integration.php');

class PensioCaptureReservationTest extends MockitTestCase
{
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantApi;
	
	public function setup()
	{
		$this->merchantApi = new PensioMerchantAPI(PENSIO_INTEGRATION_INSTALLATION, PENSIO_INTEGRATION_USERNAME, PENSIO_INTEGRATION_PASSWORD);
		$this->merchantApi->login();
	}
	
	public function testCapture()
	{
		$testReconciliationIdentifier = "reconrecon";
		$testInvoiceNumber = "invoiceinvoice";
		$testOrderId = "SomeOrderId";
		$testAmount = 42.24;
		$testSalesTax = 0.0;

		$testOrderLines = array(
			array('description'=>'SomeDescription','itemId'=>'KungFuBoy','quantity'=>1.00,'unitPrice'=>21.12,'taxAmount'=>0.00,'unitCode'=>'kg','discount'=>0.00,'goodsType'=>'item'),
			array('description'=>'SomeDescription','itemId'=>'KarateKid','quantity'=>1.00,'unitPrice'=>21.12,'taxAmount'=>0.00,'unitCode'=>'kg','discount'=>0.00,'goodsType'=>'item')
		);

		$response = $this->merchantApi->reservationOfFixedAmount(
				PENSIO_INTEGRATION_TERMINAL
				, $testOrderId
				, $testAmount
				, PENSIO_INTEGRATION_CURRENCY
				, '4111000011110000' 
				, '2020'
				, '12'
				, '123'
				, 'eCommerce');
		
		$this->assertTrue($response->wasSuccessful());

		$response = $this->merchantApi->captureReservation(
			  $response->getPrimaryPayment()->getId()
			, $testAmount
			, $testOrderLines
			, $testSalesTax
			, $testReconciliationIdentifier
			, $testInvoiceNumber
	);

		$this->assertTrue($response->wasSuccessful());
	}

}