<?php
require_once __DIR__.'/base.php';

// Different variables which are used as arguments
$orderId = 'TestOrder_'.time();
$amount = 125.55;
$currency = 'DKK';
$config = array(
    'callback_form'           => 'http://demoshop.valitor.com/Form', 'callback_ok' => 'http://demoshop.valitor.com/Ok', 'callback_fail' => 'http://demoshop.valitor.com/Fail', 'callback_redirect' => ''     // See documentation
    , 'callback_open'         => ''         // See documentation
    , 'callback_notification' => '', // See documentation
);

/**
 * @var ValitorMerchantAPI                  $api
 * @var ValitorCreatePaymentRequestResponse $response
 */
$response = $api->createPaymentRequest(
    $terminal,
    $orderId,
    $amount,
    $currency,
    null,
    null,
    null,
    null,
    $config
);
if (!$response->wasSuccessful()) {
    throw new Exception('Create payment failed: '.$response->getErrorMessage());
}
echo 'Successful createPaymentRequest'.PHP_EOL;
echo 'Redirect URL: '.$response->getRedirectURL();
