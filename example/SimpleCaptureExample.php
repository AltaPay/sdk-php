<?php
require_once __DIR__.'/base.php';
$api = InitializeValitorMerchantAPI();
$terminal = 'Some Terminal'; // change this to one of the test terminals supplied in the welcome email

// Different variables which are used as arguments
$amount = 125.55;
$transactionId = reserveAmount($api, $terminal, $amount);

$response = $api->captureReservation($transactionId);
if (!$response->wasSuccessful()) {
    throw new Exception('Capture failed: '.$response->getErrorMessage());
}
echo 'Successful capture';
