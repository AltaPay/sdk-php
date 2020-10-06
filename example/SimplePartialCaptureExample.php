<?php
require_once __DIR__.'/base.php';
$api = InitializeAltapayMerchantAPI();
$terminal = 'Some Terminal'; // change this to one of the test terminals supplied in the welcome email

// Different variables which are used as arguments
$fullAmount = 1290.00;
$partialAmount = 560.00;
$transactionId = reserveAmount($api, $terminal, $fullAmount);

$response = $api->captureReservation($transactionId, $partialAmount);
if (!$response->wasSuccessful()) {
    throw new Exception('Partial capture failed: '.$response->getErrorMessage());
}
echo 'Successful partial capture';
