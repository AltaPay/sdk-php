<?php
require_once __DIR__.'/base.php';

// Different variables which are used as arguments
$fullAmount = 1290.00;
$partialAmount = 560.00;
$transactionId = reserveAmount($api, $terminal, $fullAmount);

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
    if (!$response->wasSuccessful()) {
        throw new Exception('Amount reservation failed: '.$response->getErrorMessage());
    }
    return $response->getPrimaryPayment()->getId();
}

/**
 * @var ValitorMerchantAPI     $api
 * @var ValitorCaptureResponse $response
 */
$response = $api->captureReservation($transactionId, $partialAmount);
if (!$response->wasSuccessful()) {
    throw new Exception('Partial capture failed: '.$response->getErrorMessage());
}
echo 'Successful partial capture';
