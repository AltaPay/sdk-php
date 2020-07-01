<?php
require_once __DIR__.'/base.php';
$api = InitializeValitorMerchantAPI();

// Different variables used as arguments
$subscriptionId = '2';
$amount = 2500.00;
$currency = 'DKK';
$cardToken = '193c93414f049ee89ef9381320adb334d7e49afc';
$terminalName = 'Valitor Surcharge Test Terminal';

// Example 1
$response = $api->calculateSurchargeForSubscription($subscriptionId, $amount);
if (!$response->wasSuccessful()) {
    throw new Exception('Could not calculate surcharge amount: '.$response->getErrorMessage());
}
echo '[Case 1] Surcharge Amount: '.$response->getSurchargeAmount()."\n";

$response = $api->chargeSubscription($subscriptionId, (float)bcadd((string)$amount, $response->getSurchargeAmount(), 2));
$payment = $response->getPrimaryPayment();
if (!$response->wasSuccessful() || !$payment) {
    throw new Exception('Could not capture including surcharge amount: '.$response->getErrorMessage());
}
echo '[Case 1] Captured Amount: '.$payment->getCapturedAmount()."\n";

// Example 2
$currency = 'XXX';
$response = $api->calculateSurcharge($terminalName, $cardToken, $amount, $currency);
if (!$response->wasSuccessful()) {
    throw new Exception('Could not get surcharge amount: '.$response->getErrorMessage());
}
echo '[Case 2] Surcharge Amount: '.$response->getSurchargeAmount()."\n";

$response = $api->chargeSubscription($subscriptionId, (float)bcadd((string)$amount, $response->getSurchargeAmount(), 2));
$payment = $response->getPrimaryPayment();
if (!$response->wasSuccessful() || !$payment) {
    throw new Exception('Could not capture including surcharge amount: '.$response->getErrorMessage());
}
echo '[Case 2] Captured Amount: '.$payment->getCapturedAmount()."\n";
