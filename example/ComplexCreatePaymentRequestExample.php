<?php
require_once(dirname(__FILE__).'/base.php');


// The details for the order
$orderid = 'order'.time();
$amount = 45.25;
$currencyCode = 'DKK';
$paymentType = 'payment';
$customerInfo = array(
			'billing_postal'=> '2860',
			'billing_country'=> 'DK', // 2 character ISO-3166
			'billing_address'=> 'Rosenkæret 13',
			'billing_city'=> 'Søborg',
			'billing_region'=> null,
			'billing_firstname'=> 'Jens',
			'billing_lastname'=> 'Lyn',
			'email'=>'testperson@mydomain.com',
			'shipping_postal'=> '2860',
			'shipping_country'=> 'DK', // 2 character ISO-3166
			'shipping_address'=> 'Rosenkæret 17',
			'shipping_city'=> 'Søborg',
			'shipping_region'=> null,
			'shipping_firstname'=> 'Snej',
			'shipping_lastname'=> 'Nyl',
); // See the documentation for further details
$cookie = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : 'somecookie=tastesgood';
$language = 'en';
$config = array(
		'callback_form' => 'http://demoshop.pensio.com/Form'
		,'callback_ok' => 'http://demoshop.pensio.com/Ok'
		,'callback_fail' => 'http://demoshop.pensio.com/Fail'
		,'callback_redirect' => ''     // See documentation if this is needed
		,'callback_open' => ''         // See documentation if this is needed
		,'callback_notification' => '' // See documentation if this is needed
);
$transaction_info = array('auxkey'=>'aux data'); // this can be left out.

//initialize orderlines
$orderLines = array(
	array(
		  'description' => 'An even faster Santa Claus'
		, 'itemId' => 'SantaClausTurbo'
		, 'quantity' => 165.43
		, 'unitPrice' => 13.37
		, 'taxAmount' => 0.42
		, 'taxPercent' => 15
		, 'unitCode' => 'kg'
		, 'goodsType' => 'item'
	)
	, array(
		  'description' => 'Shipping fee'
		, 'itemId' => 'ShipShip'
		, 'quantity' => 1
		, 'unitPrice' => 5
		, 'goodsType' => 'shipping'
	)
);

//call createPaymentRequest method
$response = $api->createPaymentRequest(
			  $terminal
			, $orderid
			, $amount
			, $currencyCode
			, $paymentType
			, $customerInfo
			, $cookie
			, $language
			, $config
			, $transaction_info
			, $orderLines
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