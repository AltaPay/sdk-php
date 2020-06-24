<?php
require_once __DIR__.'/base.php';

// Different variables, which are used as arguments
$fullAmount = 1290.00;
$partialAmount = 560.00;
/**
 * @var string $transactionId
 */
$transactionId = reserveAndCapture($api, $terminal, $fullAmount);

/**
 * Helper method for reserving the order amount
 * If success then the amount is captured
 * Obs: the amount cannot be captured if is not reserved firstly.
 *
 * @param $api ValitorMerchantAPI
 * @param $terminal string
 * @param $amount float
 *
 * @throws Exception
 *
 * @return mixed
 */
function reserveAndCapture($api, $terminal, $amount)
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
     * @var ValitorMerchantAPI         $api
     * @var ValitorReservationResponse $response
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
        /**
         * @var string $transactionId
         */
        $transactionId = $response->getPrimaryPayment()->getId();
        /**
         * Capture the amount based on the fetched transaction ID.
         *
         * @var ValitorCaptureResponse $captureResponse
         * @var ValitorMerchantAPI     $api
         */
        $captureResponse = $api->captureReservation($transactionId);
        if ($captureResponse->wasSuccessful()) {
            return $transactionId;
        } else {
            throw new Exception('Capture failed: '.$response->getErrorMessage());
        }
    } else {
        throw new Exception('Reservation failed: '.$response->getErrorMessage());
    }
}

/**
 * @var ValitorMerchantAPI    $api
 * @var ValitorRefundResponse $response
 */
$response = $api->refundCapturedReservation($transactionId, $partialAmount);
if ($response->wasSuccessful()) {
    echo 'Successful refund';
} else {
    throw new Exception('Partial refund failed: '.$response->getErrorMessage());
}
