<?php

$callbackHandler = new ValitorCallbackHandler();
// Load an example of reservation and capture request where Transaction element is not present
$xml = file_get_contents(__DIR__.'/xml/CallbackXML_MobilePayError.xml');

try {
    /** @var ValitorCaptureRecurringResponse $response */
    $response = $callbackHandler->parseXmlResponse($xml);
    if ($response->getPrimaryPayment()->getCapturedAmount() > 0) {
        echo 'The capture was successful for the amount '.number_format($response->getPrimaryPayment()->getCapturedAmount(), 2).PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error in the xml response: '.$e->getMessage();
    //As suggestion: a new createPayment request can be made from here
}
