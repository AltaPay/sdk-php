<?php
require_once(dirname(__FILE__).'/base.php');

$amount = 220.00;

//Transaction ID is returned from the gateway when payment request was successful
$transaction_id = reserveAndCapture($api, $terminal, $amount);

//initialize orderlines
$order_lines = array(
	array(
		  'description' => 'Item1'
		, 'itemId' => 'Item1'
		, 'quantity' => 5
		, 'unitPrice' => 12.0
		, 'taxAmount' => 0.0
		, 'taxPercent' => 0.0
		, 'unitCode' => 'kg'
		, 'goodsType' => 'item'
    ),
    array(
        'description' => 'Item2'
      , 'itemId' => 'Item2'
      , 'quantity' => 10
      , 'unitPrice' => 15.0
      , 'taxAmount' => 0.0
      , 'taxPercent' => 0.0
      , 'unitCode' => 'kg'
      , 'goodsType' => 'item'
  )
	, array(
		  'description' => 'Shipping fee'
		, 'itemId' => 'ShipShip'
		, 'quantity' => 1
		, 'unitPrice' => 10
		, 'goodsType' => 'shipping'
	)
);

//call refund method
$response = $api->refundCapturedReservation($transaction_id, $amount, $order_lines);

//response contains wasSuccessful() method which returns TRUE if request was successful or FALSE if not
if ($response->wasSuccessful()) 
{
    //refund was successful
    print('Successful refund');
}
else 
{
    //getErrorMessage() method returns description about what went wrong
    print('Error message: '.$response->getErrorMessage());
}



//helper method to reserve amount for the payment and capture it in order to be refunded
//method is returning transaction_id value
function reserveAndCapture($api, $terminal, $amount) {
    $order_id = 'order'.time();
    $currency_code = 'DKK';
    $payment_type = 'payment';
    $pan = '4111000011110000';
    $cvc = '111';
    $expiry_month = '12';
    $expiry_year = '2018';
    $transaction_info = array();
    $order_lines = array();

    $response = $api->reservation(
    $terminal
        ,$order_id
        ,$amount
        ,$currency_code
        ,NULL
        ,$pan
        ,$expiry_month
        ,$expiry_year
        ,$cvc
        ,$transaction_info
        ,$payment_type
        ,NULL
        ,NULL
        ,NULL
        ,NULL
        ,NULL
        ,NULL
        ,$order_lines
    );
    if($response->wasSuccessful())
    {
        $transaction_id = $response->getPrimaryPayment()->getId();

        $response = $api->captureReservation($transaction_id);
        if ($response->wasSuccessful()) 
        {
            return $transaction_id;
        }
        else 
        {
            print('Error message: '.$response->getErrorMessage());
        }
    }
    else
    {
        print("Error message: ".$response->getErrorMessage());
    }
}