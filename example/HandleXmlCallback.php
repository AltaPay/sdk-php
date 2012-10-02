<?php

require_once(dirname(__FILE__).'/../PensioCallbackHandler.class.php');
$callbackHandler = new PensioCallbackHandler();



// The XML would normally be POST'ed back to your okay/fail page as a parameter named 'xml'. 
// But for these tests, we just pump some static XML in to show you what you will get from the XML. 
$xml = file_get_contents(dirname(__FILE__).'/xml/CallbackXML_subscriptionAndCharge_released.xml');
$response = $callbackHandler->parseXmlResponse($xml);
if($response->wasSubscriptionReleased())
{
	print("The subscription was released\n");
}
if($response->getPrimaryPayment()->getCapturedAmount() > 0)
{
	print("The capture was successful, we captured: ".number_format($response->getPrimaryPayment()->getCapturedAmount(), 2)."\n");
}

//print_r($response);
