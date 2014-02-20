<?php
require_once(dirname(__FILE__).'/base.php');


$payment_id = '123123123'; // Insert real payment-id here

$response = $api->refundCapturedReservation($payment_id, $amount);
if(!$response->wasSuccessful())
{
	throw new Exception("Could not refund the payment: ".$response->getErrorMessage());
}
