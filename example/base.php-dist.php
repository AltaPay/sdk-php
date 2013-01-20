<?php

require_once(dirname(__FILE__).'/../PensioMerchantAPI.class.php');

$baseURL = "https://testgateway.pensio.com/";
$username = 'username';
$password = 'password';
$terminal = 'Some Terminal';

$api = new PensioMerchantAPI($baseURL, $username, $password, /*IPensioCommunicationLogger $logger = */null);

$response = $api->login();
if(!$response->wasSuccessful())
{
	throw new Exception("Could not login to the Merchant API: ".$response->getErrorMessage());
}