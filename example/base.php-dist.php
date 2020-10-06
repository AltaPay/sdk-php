<?php
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Helper method for reserving the payment amount.
 * Obs: the amount cannot be captured if is not reserved firstly.
 *
 * @param AltapayMerchantAPI               $api
 * @param string                           $terminal
 * @param float                            $amount
 * @param array<int, array<string, mixed>> $orderLines [array()]
 *
 * @throws Exception
 *
 * @return string
 */
function reserveAmount($api, $terminal, $amount, $orderLines = array())
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
        $paymentSource,
        null,
        null,
        null,
        null,
        null,
        $orderLines
    );
    $payment = $response->getPrimaryPayment();
    if (!$response->wasSuccessful() || !$payment) {
        throw new Exception('Amount reservation failed: '.$response->getErrorMessage());
    }
    return $payment->getId();
}

/**
 * Helper method for reserving the order amount
 * If success then the amount is captured
 * Obs: the amount cannot be captured if is not reserved firstly.
 *
 * @param AltapayMerchantAPI               $api
 * @param string                           $terminal
 * @param float                            $amount
 * @param array<int, array<string, mixed>> $orderLines [array()]
 *
 * @throws Exception
 *
 * @return string
 */
function reserveAndCapture($api, $terminal, $amount, $orderLines = array())
{
    $transactionId = reserveAmount($api, $terminal, $amount, $orderLines);
    // Capture the amount based on the fetched transaction ID.
    $response = $api->captureReservation($transactionId);
    if (!$response->wasSuccessful()) {
        throw new Exception('Capture failed: '.$response->getErrorMessage());
    }
    return $transactionId;
}

/**
 * @return AltapayMerchantAPI
 */
function InitializeAltapayMerchantAPI()
{
    $baseURL = 'https://testgateway.altapay.com/';
    $username = 'username';
    $password = 'password';

    $api = new AltapayMerchantAPI($baseURL, $username, $password, /*IAltapayCommunicationLogger $logger = */ null);
    $response = $api->login();
    if (!$response->wasSuccessful()) {
        /*
         * If you get the following error when trying to login...
         * SSL certificate problem: unable to get local issuer certificate
         *
         * You need to update your Thawte Root Certificate
         * 1) Get the certificate from http://www.thawte.com/roots/thawte_Server_CA.pem
         * 2) Add it/update the certificate in your operating system's certificate store
         */
        throw new Exception('Could not login to the Merchant API: '.$response->getErrorMessage());
    }
    return $api;
}
