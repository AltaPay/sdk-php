<?php

require_once(dirname(__DIR__).'/lib/PensioMerchantAPI.class.php');

$baseURL = "https://testgateway.pensio.com/";
$username = 'username';
$password = 'password';
$terminal = 'Some Terminal'; // change this to one of the test terminals supplied in the welcome email

$api = new PensioMerchantAPI($baseURL, $username, $password, /*IPensioCommunicationLogger $logger = */null);

$response = $api->login();
if(!$response->wasSuccessful())
{
	throw new Exception("Could not login to the Merchant API: ".$response->getErrorMessage());
}


/**
 * If you get the following error when trying to login...
 * SSL certificate problem: unable to get local issuer certificate
 *
 * You need to update your Thawte root certificate
 * 1) Get the certificate from http://www.thawte.com/roots/thawte_Server_CA.pem
 * 2) Add it/update the certificate in your operating system's certificate store
 */