<?php
require_once __DIR__.'/base.php';
$api = InitializeValitorMerchantAPI();
$terminal = 'Some Terminal'; // change this to one of the test terminals supplied in the welcome email

// Different variables, which are used as arguments
$amount = 215.00;
$transactionId = reserveAndCapture($api, $terminal, $amount);

$response = $api->refundCapturedReservation($transactionId, $amount);
if (!$response->wasSuccessful()) {
    throw new Exception('Refund failed: '.$response->getErrorMessage());
}
echo 'Successful refund';
