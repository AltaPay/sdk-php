<?php

$callbackHandler = new AltaPayCallbackHandler();
// Load an example of reservation and capture request
$xml = file_get_contents(__DIR__.'/xml/CallbackXML_reservationAndCapture.xml') ?: '';

$response = $callbackHandler->parseXmlResponse($xml);
if (!$response->wasSuccessful()) {
    throw new Exception('Reservation and capture failed: '.$response->getErrorMessage());
}
echo 'Reservation and capture was successful'.PHP_EOL;
$payment = $response->getPrimaryPayment();
if ($payment && $payment->getCapturedAmount() > 0) {
    echo 'The capture was successful, the amount of '.number_format((float)$payment->getCapturedAmount(), 2).' was captured'.PHP_EOL;
    $avsResponse = $payment->getAddressVerification();
    if ($avsResponse != '') {
        echo 'AVS response: '.$avsResponse.PHP_EOL;
        echo 'AVS description: '.$payment->getAddressVerificationDescription().PHP_EOL;
    } else {
        echo 'No AVS response'.PHP_EOL;
    }
}
