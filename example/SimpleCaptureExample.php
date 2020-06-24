<?php
require_once __DIR__.'/base.php';

// Different variables which are used as arguments
$amount = 125.55;
$transactionId = reserveAmount($api, $terminal, $amount);

/**
 * Helper method for reserving the payment amount.
 *
 * @param ValitorMerchantAPI $api
 * @param string             $terminal
 * @param float              $amount
 *
 * @throws Exception
 *
 * @return mixed
 */
function reserveAmount($api, $terminal, $amount)
{
    $orderId = 'order_'.time();
    $transactionInfo = array();
    $cardToken = null;
    // Credit card details
    $currencyCode = 'DKK';
    $paymentType = 'payment';
    $paymentSource = 'eCommerce';
    $pan = '4111000011110000';
    $cvc = '111';
    $expiryMonth = '12';
    $expiryYear = '2018';

    /**
     * @var ValitorCreatePaymentRequestResponse $response
     */
    $response = $api->reservation(
        $terminal,
        $orderId,
        $amount,
        $currencyCode,
        $cardToken,
        $pan,
        $expiryMonth,
        $expiryYear,
        $cvc,
        $transactionInfo,
        $paymentType,
        $paymentSource
    );
    if ($response->wasSuccessful()) {
        return $response->getPrimaryPayment()->getId();
    } else {
        throw new Exception('Amount reservation failed: '.$response->getErrorMessage());
    }
}

/**
 * @var ValitorMerchantAPI     $api
 * @var ValitorCaptureResponse $response
 */
$response = $api->captureReservation($transactionId);
if ($response->wasSuccessful()) {
    echo 'Successful capture';
} else {
    throw new Exception('Capture failed: '.$response->getErrorMessage());
}
