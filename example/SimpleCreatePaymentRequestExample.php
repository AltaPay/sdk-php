<?php
require_once(dirname(__FILE__).'/base.php');


// Order ID, terminal, amount and currency are mandatory fields for createPaymentRequest method
$orderid = 'order'.time();
$amount = 125.55;
$currencyCode = 'DKK';

//initialize callback URLs
$config = array(
    'callback_form' => 'http://demoshop.pensio.com/Form'
    ,'callback_ok' => 'http://demoshop.pensio.com/Ok'
    ,'callback_fail' => 'http://demoshop.pensio.com/Fail'
    ,'callback_redirect' => ''     // See documentation if this is needed
    ,'callback_open' => ''         // See documentation if this is needed
    ,'callback_notification' => '' // See documentation if this is needed
);

//call createPaymentRequest method
$response = $api->createPaymentRequest(
			 $terminal
			,$orderid
			,$amount
			,$currencyCode
			,NULL
			,NULL
			,NULL
			,NULL
			,$config
);

//response contains wasSuccessful() method which returns TRUE if request was successful or FALSE if not
if($response->wasSuccessful())
{
	//createPaymentRequest was successful
	print("Successful createPaymentRequest\n");
	//URL to the payment form page to redirect user
    print("Redirection URL: ".$paymentFormURL = $response->getRedirectURL());
}
else
{
	//getErrorMessage() method returns description about what went wrong
    print("Error message: ".$response->getErrorMessage());
}