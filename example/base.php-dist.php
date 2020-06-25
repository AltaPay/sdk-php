<?php
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * @return ValitorMerchantAPI
 */
function InitializeValitorMerchantAPI()
{
    $baseURL = 'https://testgateway.valitor.com/';
    $username = 'username';
    $password = 'password';
    $terminal = 'Some Terminal'; // change this to one of the test terminals supplied in the welcome email

    $api = new ValitorMerchantAPI($baseURL, $username, $password, /*IValitorCommunicationLogger $logger = */ null);
    $response = $api->login();
    if (!$response->wasSuccessful()) {
        /*
         * If you get the following error when trying to login...
         * SSL certificate problem: unable to get local issuer certificate
         *
         * You need to update your Thawte Root Certificate
         * 1) Get the certificate from http://www.thawte.com/roots/thawte_Server_CA.pem
         * 2) Add it/update the certificate in your operating system's certificate store
         */
        throw new Exception('Could not login to the Merchant API: '.$response->getErrorMessage());
    }
    return $api;
}
