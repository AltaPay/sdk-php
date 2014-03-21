<?php

require_once(dirname(__FILE__).'/../PensioMerchantAPI.class.php');

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
 * ...then take a look at http://stackoverflow.com/a/19149687
 *
 * Basically you need to update your list of certificate authorities
 * 1) Download http://curl.haxx.se/ca/cacert.pem
 * 2) Add the following to your php.ini
 *    curl.cainfo=<path-to>cacert.pem
 */