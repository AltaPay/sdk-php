<?php

$callbackHandler = new ValitorCallbackHandler();
// Load an example of reservation and capture request
// The XML would normally be POST'ed back to the okay/fail page as a parameter named 'xml'
$xml = file_get_contents(__DIR__.'/xml/CallbackXML_subscriptionAndCharge_released.xml') ?: '';

try {
    /** @var ValitorCaptureRecurringResponse $response */
    $response = $callbackHandler->parseXmlResponse($xml);
    if ($response->wasSubscriptionReleased()) {
        echo 'The subscription was released'.PHP_EOL;
        $amount = (float)$response->getPrimaryPayment()->getCapturedAmount();
        if ($amount > 0) {
            echo 'The capture was successful for the amount '.number_format($amount, 2).PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo 'Error in the response: '.$e->getMessage();
}
