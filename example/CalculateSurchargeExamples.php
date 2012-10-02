<?php

require_once(dirname(__FILE__).'/../PensioMerchantAPI.class.php');

$baseURL = "http://gateway.dev.pensio.com/";
$username = 'shop api';
$password = 'testpassword';

$api = new PensioMerchantAPI($baseURL, $username, $password, /*IPensioCommunicationLogger $logger = */null);

$response = $api->login();
if(!$response->wasSuccessful())
{
	throw new Exception("Could not login to the Merchant API: ".$response->getErrorMessage());
}

$subscriptionId = 2;
$amount = 2500.00;
$response = $api->calculateSurchargeForSubscription($subscriptionId, $amount, $currency);
//print_r($response);
if(!$response->wasSuccessful())
{
	throw new Exception("Could not get surcharge amount: ".$response->getErrorMessage());
}
print("[Case 1] Surcharge Amount: ".$response->getSurchargeAmount()."\n");
$response = $api->captureRecurring($subscriptionId, bcadd($amount, $response->getSurchargeAmount(), 2));
if(!$response->wasSuccessful())
{
	throw new Exception("Could not capture including surcharge amount: ".$response->getErrorMessage());
}
print("[Case 1] Captured Amount: ".$response->getPrimaryPayment()->getCapturedAmount()."\n");


$amount = 123.37;
$currency = 'XXX';
$response = $api->calculateSurcharge("Pensio Surcharge Test Terminal", '193c93414f049ee89ef9381320adb334d7e49afc', $amount, $currency);
//print_r($response);
if(!$response->wasSuccessful())
{
	throw new Exception("Could not get surcharge amount: ".$response->getErrorMessage());
}
print("[Case 2] Surcharge Amount: ".$response->getSurchargeAmount()."\n");
$response = $api->captureRecurring($subscriptionId, bcadd($amount, $response->getSurchargeAmount(), 2));
if(!$response->wasSuccessful())
{
	throw new Exception("Could not capture including surcharge amount: ".$response->getErrorMessage());
}
print("[Case 2] Captured Amount: ".$response->getPrimaryPayment()->getCapturedAmount()."\n");
