<?php
require_once(dirname(__FILE__).'/../lib/bootstrap.php');

class PensioPaymentTests extends MockitTestCase
{
	
	public function setup()
	{
		
	}
	
	public function testParsingOfSimpleXml()
	{
		$xml = new SimpleXMLElement('<Transaction><PaymentNatureService /><ReconciliationIdentifiers /></Transaction>');
		$payment = new PensioAPIPayment($xml);
	}
}