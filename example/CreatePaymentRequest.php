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
				  'callback_form' => 'http://shopdomain.url/pensiopayment/form.php'
				, 'callback_ok' => 'http://shopdomain.url/pensiopayment/ok.php'
				, 'callback_fail' => 'http://shopdomain.url/pensiopayment/fail.php'
				, 'callback_redirect' => ''     // See documentation if this is needed
				, 'callback_open' => ''         // See documentation if this is needed
				, 'callback_notification' => '' // See documentation if this is needed
);
$transaction_info = array('auxkey'=>'aux data'); // this can be left out.

/**
 * Order lines (optional, but recommended)
 */
$orderLines = array(
	array(
		  'description' => 'An even faster Santa Claus'
		, 'itemId' => 'SantaClausTurbo'
		, 'quantity' => 165.43
		, 'unitPrice' => 13.37
		// optional stuff
		, 'taxAmount' => 0.42
		, 'unitCode' => 'kg'
		, 'goodsType' => 'item'
	)
	, array(
		  'description' => 'Shipping fee'
		, 'itemId' => 'ShipShip'
		, 'quantity' => 1
		, 'unitPrice' => 5
		// optional stuff
		, 'goodsType' => 'shipping'
	)
);

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
			, $orderLines // optional, but recommended
);
if(!$response->wasSuccessful())
{
	throw new Exception("Could not create the payment request: ".$response->getErrorMessage());
}

// TODO: redirect the user to the URL:
print("Then you can invoke: header('Location: ".$response->getRedirectURL()."');\n");