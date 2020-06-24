<?php

$callbackHandler = new ValitorCallbackHandler();
// Load an example of reservation and capture request
$xml = file_get_contents(__DIR__.'/xml/CallbackXML_reservationAndCapture.xml');

/**
 * @var ValitorOmniReservationResponse $response
 */
$response = $callbackHandler->parseXmlResponse($xml);
if ($response->wasSuccessful()) {
    echo 'Reservation and capture was successful'.PHP_EOL;
    if ($response->getPrimaryPayment()->getCapturedAmount() > 0) {
        echo 'The capture was successful, the amount of '.number_format($response->getPrimaryPayment()->getCapturedAmount(), 2).' was captured'.PHP_EOL;
        $avsResponse = $response->getPrimaryPayment()->getAddressVerification();
        if ($avsResponse != '') {
            echo 'AVS response: '.$avsResponse.PHP_EOL;
            echo 'AVS description: '.$response->getPrimaryPayment()->getAddressVerificationDescription().PHP_EOL;
        } else {
            echo 'No AVS response'.PHP_EOL;
        }
    }
} else {
    throw new Exception('Reservation and capture failed: '.$response->getErrorMessage());
}
