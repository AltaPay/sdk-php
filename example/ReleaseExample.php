<?php
require_once(dirname(__FILE__).'/base.php');

//Transaction ID is returned from the gateway when payment request was successful
$transaction_id = reserveAmount($api, $terminal);

//call release method
$response = $api->releaseReservation($transaction_id);

//response contains wasSuccessful() method which returns TRUE if request was successful or FALSE if not
if ($response->wasSuccessful()) 
{
    //release was successful
    print('Successful release');
}
else 
{
    //getErrorMessage() method returns description about what went wrong
    print('Error message: '.$response->getErrorMessage());
}



//helper method to reserve amount for the payment in order to be released
//method is returning transaction_id value
function reserveAmount($api, $terminal) {
    $order_id = 'order'.time();
    $amount = 125.55;
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
        return $response->getPrimaryPayment()->getId();
    }
    else
    {
        print("Error message: ".$response->getErrorMessage());
    }
}