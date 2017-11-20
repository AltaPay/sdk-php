<?php

require_once(dirname(__FILE__).'/../lib/PensioCallbackHandler.class.php');
$callbackHandler = new PensioCallbackHandler();

//PensioReservationResponse will be understood
$xml = file_get_contents(dirname(__FILE__).'/xml/CallbackXML_reservationAndCapture.xml');
/* @var $response PensioReservationResponse */
$response = $callbackHandler->parseXmlResponse($xml);
if($response->wasSuccessful())
{
	print("The payment was successful\n");
}
if($response->getPrimaryPayment()->getCapturedAmount() > 0)
{
	print("The capture was successful, we captured: ".number_format($response->getPrimaryPayment()->getCapturedAmount(), 2)."\n");
	print("AVS Response: ".$response->getPrimaryPayment()->getAddressVerification()."\n");
	print("AVS Description: ".$response->getPrimaryPayment()->getAddressVerificationDescription()."\n");
}