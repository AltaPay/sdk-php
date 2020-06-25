<?php

class ValitorMerchantAPI
{
    /** @var string */
    private $baseURL;
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var bool */
    private $connected = false;
    /** @var IValitorCommunicationLogger */
    private $logger;
    /** @var IValitorHttpUtils */
    private $httpUtil;

    /**
     * @param string $baseURL
     * @param string $username
     * @param string $password
     */
    public function __construct($baseURL, $username, $password, IValitorCommunicationLogger $logger = null, IValitorHttpUtils $httpUtil = null)
    {
        $this->connected = false;
        $this->baseURL = rtrim($baseURL, '/');
        $this->username = $username;
        $this->password = $password;
        $this->logger = $logger;

        if ($httpUtil === null) {
            if (function_exists('curl_init')) {
                $httpUtil = new ValitorCurlBasedHttpUtils();
            } elseif (ini_get('allow_url_fopen')) {
                $httpUtil = new ValitorFOpenBasedHttpUtils();
            } else {
                throw new Exception("Neither allow_url_fopen nor cURL is installed, we cannot communicate with Valitor's Payment Gateway without at least one of them.");
            }
        }
        $this->httpUtil = $httpUtil;
    }

    /**
     * Check api connection.
     *
     * @throws Exception
     *
     * @return void
     */
    private function checkConnection()
    {
        if (!$this->connected) {
            throw new Exception('Not Connected, invoke login() before using any API calls');
        }
    }

    /**
     * Check the state of api connection.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Generated the masked pan for provided string.
     *
     * @parm string $pan
     *
     * @return string
     */
    private function maskPan($pan)
    {
        if (strlen($pan) >= 10) {
            return substr($pan, 0, 6).str_repeat('x', strlen($pan) - 10).substr($pan, -4);
        }
        return $pan;
    }

    /**
     * Check API connection response and return the status.
     *
     * @param string   $method
     * @param string[] $args
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return SimpleXMLElement|string
     */
    private function callAPIMethod($method, array $args = array())
    {
        $absoluteUrl = $this->baseURL.'/merchant/API/'.$method;

        $logId = null;
        if ($this->logger !== null) {
            $loggedArgs = $args;
            if (isset($loggedArgs['cardnum'])) {
                $loggedArgs['cardnum'] = $this->maskPan($loggedArgs['cardnum']);
            }
            if (isset($loggedArgs['cvc'])) {
                $loggedArgs['cvc'] = str_repeat('x', strlen($loggedArgs['cvc']));
            }
            $logId = $this->logger->logRequest($absoluteUrl.'?'.http_build_query($loggedArgs));
        }

        $request = new ValitorHttpRequest();
        $request->setUrl($absoluteUrl);
        $request->setParameters($args);
        $request->setUser($this->username);
        $request->setPass($this->password);
        $request->setMethod('POST');
        $request->addHeader('x-valitor-client-version: '.VALITOR_VERSION);

        $response = $this->httpUtil->requestURL($request);

        if ($this->logger !== null) {
            $this->logger->logResponse($logId, print_r($response, true));
        }

        if ($response->getConnectionResult() == ValitorHttpResponse::CONNECTION_OKAY) {
            if ($response->getHttpCode() == 200) {
                if (stripos($response->getContentType(), 'text/xml') !== false) {
                    try {
                        return new SimpleXMLElement($response->getContent());
                    } catch (Exception $e) {
                        if ($e->getMessage() == 'String could not be parsed as XML') {
                            throw new ValitorInvalidResponseException('Unparsable XML Content in response');
                        }
                        throw new ValitorUnknownMerchantAPIException($e);
                    }
                } elseif (stripos($response->getContentType(), 'text/csv') !== false) {
                    return $response->getContent();
                }
                throw new ValitorInvalidResponseException('Non XML ContentType (was: '.$response->getContentType().')');
            }
            if ($response->getHttpCode() == 401) {
                throw new ValitorUnauthorizedAccessException($absoluteUrl, $this->username);
            }
            throw new ValitorInvalidResponseException('Non HTTP 200 Response: '.$response->getHttpCode());
        }
        if ($response->getConnectionResult() == ValitorHttpResponse::CONNECTION_REFUSED) {
            throw new ValitorConnectionFailedException($absoluteUrl, 'Connection refused');
        }
        if ($response->getConnectionResult() == ValitorHttpResponse::CONNECTION_TIMEOUT) {
            throw new ValitorConnectionFailedException($absoluteUrl, 'Connection timed out');
        }
        if ($response->getConnectionResult() == ValitorHttpResponse::CONNECTION_READ_TIMEOUT) {
            throw new ValitorRequestTimeoutException($absoluteUrl);
        }
        throw new ValitorUnknownMerchantAPIException();
    }

    /**
     * @throws ValitorMerchantAPIException
     *
     * @return ValitorFundingListResponse
     */
    public function getFundingList($page = 0)
    {
        $this->checkConnection();

        return new ValitorFundingListResponse($this->callAPIMethod('fundingList', array('page' => $page)));
    }

    /**
     * @throws Exception
     *
     * @return bool|string
     */
    public function downloadFundingCSV(ValitorAPIFunding $funding)
    {
        $this->checkConnection();

        $request = new ValitorHttpRequest();
        $request->setUrl($funding->getDownloadLink());
        $request->setUser($this->username);
        $request->setPass($this->password);
        $request->setMethod('GET');

        $response = $this->httpUtil->requestURL($request);

        if ($response->getHttpCode() == 200) {
            return $response->getContent();
        }

        return false;
    }

    /**
     * @throws Exception
     *
     * @return bool|string
     */
    public function downloadFundingCSVByLink($downloadLink)
    {
        $this->checkConnection();

        $request = new ValitorHttpRequest();

        $request->setUrl($downloadLink);
        $request->setUser($this->username);
        $request->setPass($this->password);
        $request->setMethod('GET');

        $response = $this->httpUtil->requestURL($request);

        if ($response->getHttpCode() == 200) {
            return $response->getContent();
        }

        return false;
    }

    /**
     * @param string      $apiMethod
     * @param string      $terminal
     * @param string      $shopOrderId
     * @param float       $amount
     * @param string      $currency
     * @param string      $creditCardNumber
     * @param string      $creditCardExpiryYear
     * @param string      $creditCardExpiryMonth
     * @param string|null $creditCardToken
     * @param string      $cvc
     * @param string      $type
     * @param string      $paymentSource
     * @param array       $customerInfo
     * @param array       $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    private function reservationInternal(
        $apiMethod,
        $terminal,
        $shopOrderId,
        $amount,
        $currency,
        $creditCardNumber,
        $creditCardExpiryYear,
        $creditCardExpiryMonth,
        $creditCardToken,
        $cvc,
        $type,
        $paymentSource,
        array $customerInfo,
        array $transactionInfo
    ) {
        $this->checkConnection();

        $args = array(
            'terminal'       => $terminal,
            'shop_orderid'   => $shopOrderId,
            'amount'         => $amount,
            'currency'       => $currency,
            'cvc'            => $cvc,
            'type'           => $type,
            'payment_source' => $paymentSource,
        );
        if ($creditCardToken !== null) {
            $args['credit_card_token'] = $creditCardToken;
        } else {
            $args['cardnum'] = $creditCardNumber;
            $args['emonth'] = $creditCardExpiryMonth;
            $args['eyear'] = $creditCardExpiryYear;
        }

        if ($customerInfo !== null && is_array($customerInfo)) {
            $this->addCustomerInfo($customerInfo, $args);
        }

        // Not needed when everyone has been upgraded to 20150428
        // ====================================================================
        foreach (array('billing_city', 'billing_region', 'billing_postal', 'billing_country', 'email', 'customer_phone', 'bank_name', 'bank_phone', 'billing_firstname', 'billing_lastname', 'billing_address') as $custField) {
            if (isset($customerInfo[$custField])) {
                $args[$custField] = $customerInfo[$custField];
            }
        }
        // ====================================================================
        if (count($transactionInfo) > 0) {
            $args['transaction_info'] = $transactionInfo;
        }

        return new ValitorOmniReservationResponse(
            $this->callAPIMethod(
                $apiMethod,
                $args
            )
        );
    }

    /**
     * Fixed amount reservation.
     *
     * @param string $terminal
     * @param string $shopOrderId
     * @param float  $amount
     * @param string $currency
     * @param string $creditCardNumber
     * @param string $creditCardExpiryYear
     * @param string $creditCardExpiryMonth
     * @param string $cvc
     * @param string $paymentSource
     * @param array  $customerInfo
     * @param array  $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function reservationOfFixedAmount(
        $terminal,
        $shopOrderId,
        $amount,
        $currency,
        $creditCardNumber,
        $creditCardExpiryYear,
        $creditCardExpiryMonth,
        $cvc,
        $paymentSource,
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'reservationOfFixedAmountMOTO',
            $terminal,
            $shopOrderId,
            $amount,
            $currency,
            $creditCardNumber,
            $creditCardExpiryYear,
            $creditCardExpiryMonth,
            null // $creditCardToken
            ,
            $cvc,
            'payment',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string|null $cvc
     * @param string      $paymentSource
     * @param array       $customerInfo
     * @param array       $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function reservationOfFixedAmountMOTOWithToken(
        $terminal,
        $shopOrderId,
        $amount,
        $currency,
        $creditCardToken,
        $cvc = null,
        $paymentSource = 'moto',
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'reservationOfFixedAmountMOTO',
            $terminal,
            $shopOrderId,
            $amount,
            $currency,
            null,
            null,
            null,
            $creditCardToken,
            $cvc,
            'payment',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string $terminal
     * @param string $shopOrderId
     * @param float  $amount
     * @param string $currency
     * @param string $creditCardNumber
     * @param string $creditCardExpiryYear
     * @param string $creditCardExpiryMonth
     * @param string $cvc
     * @param string $paymentSource
     * @param array  $customerInfo
     * @param array  $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function setupSubscription(
        $terminal,
        $shopOrderId,
        $amount,
        $currency,
        $creditCardNumber,
        $creditCardExpiryYear,
        $creditCardExpiryMonth,
        $cvc,
        $paymentSource,
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'setupSubscription',
            $terminal,
            $shopOrderId,
            $amount,
            $currency,
            $creditCardNumber,
            $creditCardExpiryYear,
            $creditCardExpiryMonth,
            null // $creditCardToken
            ,
            $cvc,
            'subscription',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string      $terminal
     * @param string      $shopOrderId
     * @param float       $amount
     * @param string      $currency
     * @param string      $creditCardToken
     * @param string|null $cvc
     * @param string      $paymentSource
     * @param array       $customerInfo
     * @param array       $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function setupSubscriptionWithToken(
        $terminal,
        $shopOrderId,
        $amount,
        $currency,
        $creditCardToken,
        $cvc = null,
        $paymentSource = 'moto',
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'setupSubscription',
            $terminal,
            $shopOrderId,
            $amount,
            $currency,
            null,
            null,
            null,
            $creditCardToken,
            $cvc,
            'subscription',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string $terminal
     * @param string $shopOrderId
     * @param string $currency
     * @param string $creditCardNumber
     * @param string $creditCardExpiryYear
     * @param string $creditCardExpiryMonth
     * @param string $cvc
     * @param string $paymentSource
     * @param array  $customerInfo
     * @param array  $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function verifyCard(
        $terminal,
        $shopOrderId,
        $currency,
        $creditCardNumber,
        $creditCardExpiryYear,
        $creditCardExpiryMonth,
        $cvc,
        $paymentSource,
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'reservationOfFixedAmountMOTO',
            $terminal,
            $shopOrderId,
            1.00,
            $currency,
            $creditCardNumber,
            $creditCardExpiryYear,
            $creditCardExpiryMonth,
            null // $creditCardToken
            ,
            $cvc,
            'verifyCard',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string null $cvc
     * @param string      $paymentSource
     * @param array       $customerInfo
     * @param array       $transactionInfo
     *
     * @return ValitorOmniReservationResponse
     */
    public function verifyCardWithToken(
        $terminal,
        $shopOrderId,
        $currency,
        $creditCardToken,
        $cvc = null,
        $paymentSource = 'moto',
        array $customerInfo = array(),
        array $transactionInfo = array()
    ) {
        return $this->reservationInternal(
            'reservationOfFixedAmountMOTO',
            $terminal,
            $shopOrderId,
            1.00,
            $currency,
            null,
            null,
            null,
            $creditCardToken,
            $cvc,
            'verifyCard',
            $paymentSource,
            $customerInfo,
            $transactionInfo
        );
    }

    /**
     * @param string     $paymentId
     * @param float|null $amount
     * @param array      $orderLines
     * @param float|null $salesTax
     * @param string     $reconciliationIdentifier
     * @param string     $invoiceNumber
     * @param null       $shippingCompany
     * @param null       $trackingNumber
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCaptureResponse
     */
    public function captureReservation($paymentId, $amount = null, array $orderLines = array(), $salesTax = null, $reconciliationIdentifier = null, $invoiceNumber = null, $shippingCompany = null, $trackingNumber = null)
    {
        $this->checkConnection();

        return new ValitorCaptureResponse(
            $this->callAPIMethod(
                'captureReservation',
                array(
                    'transaction_id'            => $paymentId,
                    'amount'                    => $amount,
                    'orderLines'                => $orderLines,
                    'sales_tax'                 => $salesTax,
                    'reconciliation_identifier' => $reconciliationIdentifier,
                    'invoice_number'            => $invoiceNumber,
                    'shippingTrackingInfo'      => array(
                        'shippingCompany' => $shippingCompany,
                        'trackingNumber'  => $trackingNumber,
                    ),
                )
            )
        );
    }

    /**
     * @param string                                       $paymentId
     * @param float|null                                   $amount
     * @param array<int, array<string, float|string>>|null $orderLines
     * @param string|null                                  $reconciliationIdentifier
     * @param bool|null                                    $allowOverRefund
     * @param string|null                                  $invoiceNumber
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorRefundResponse
     */
    public function refundCapturedReservation($paymentId, $amount = null, $orderLines = null, $reconciliationIdentifier = null, $allowOverRefund = null, $invoiceNumber = null)
    {
        $this->checkConnection();

        return new ValitorRefundResponse(
            $this->callAPIMethod(
                'refundCapturedReservation',
                array(
                    'transaction_id'            => $paymentId,
                    'amount'                    => $amount,
                    'orderLines'                => $orderLines,
                    'reconciliation_identifier' => $reconciliationIdentifier,
                    'allow_over_refund'         => $allowOverRefund,
                    'invoice_number'            => $invoiceNumber,
                )
            )
        );
    }

    /**
     * @param string $paymentId
     * @param array  $orderLines
     *
     * @throws ValitorMerchantAPIException
     *
     * @return ValitorUpdateOrderResponse
     */
    public function updateOrder($paymentId, $orderLines)
    {
        if ($orderLines == null || count($orderLines) != 2) {
            throw new ValitorMerchantAPIException('orderLines must contain exactly two elements');
        }

        $this->checkConnection();

        return new ValitorUpdateOrderResponse(
            $this->callAPIMethod(
                'updateOrder',
                array(
                    'payment_id' => $paymentId,
                    'orderLines' => $orderLines,
                )
            )
        );
    }

    /**
     * @param float|null $amount
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorReleaseResponse
     */
    public function releaseReservation($paymentId, $amount = null)
    {
        $this->checkConnection();

        return new ValitorReleaseResponse(
            $this->callAPIMethod(
                'releaseReservation',
                array(
                    'transaction_id' => $paymentId,
                )
            )
        );
    }

    /**
     * @param string   $paymentId
     * @param string[] $multipleParams
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorGetPaymentResponse
     */
    public function getPayment($paymentId, $multipleParams = array())
    {
        $this->checkConnection();
        if (!empty($multipleParams)) {
            /*
               $multipleParams = array(
                'shop_orderid' => 'test1',
                'transaction_id' => '12312434324',
                'terminal' => 'Test Terminal',
                );
            */
            $requestBody = $multipleParams;
        } else {
            $requestBody = array(
                'transaction' => $paymentId,
            );
        }
        return new ValitorGetPaymentResponse($this->callAPIMethod(
            'payments',
            $requestBody
        ));
    }

    /**
     * @throws ValitorMerchantAPIException
     *
     * @return ValitorGetTerminalsResponse
     */
    public function getTerminals()
    {
        $this->checkConnection();

        return new ValitorGetTerminalsResponse($this->callAPIMethod('getTerminals'));
    }

    /**
     * @throws ValitorMerchantAPIException
     *
     * @return ValitorLoginResponse
     */
    public function login()
    {
        $this->connected = false;

        $response = new ValitorLoginResponse($this->callAPIMethod('login'));

        if ($response->getErrorCode() === '0') {
            $this->connected = true;
        }

        return $response;
    }

    /**
     * @param string                          $terminal
     * @param string                          $orderId
     * @param float                           $amount
     * @param string                          $currencyCode
     * @param string                          $paymentType
     * @param array<string, string|null>|null $customerInfo
     * @param string|null                     $cookie
     * @param string|null                     $language
     * @param string                          $reconciliationIdentifier
     * @param array                           $config
     * @param array                           $transactionInfo
     * @param array                           $orderLines
     * @param bool                            $accountOffer
     * @param null                            $ccToken
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCreatePaymentRequestResponse
     */
    public function createPaymentRequest(
        $terminal,
        $orderId,
        $amount,
        $currencyCode,
        $paymentType,
        $customerInfo = null,
        $cookie = null,
        $language = null,
        array $config = array(),
        array $transactionInfo = array(),
        array $orderLines = array(),
        $accountOffer = false,
        $ccToken = null,
        $reconciliationIdentifier = null,
        $invoiceNumber = null,
        $fraudService = null,
        $paymentSource = null,
        $shippingMethod = null,
        $customerCreatedDate = null,
        $organizationNumber = null,
        $salesTax = null
    ) {
        $args = array(
            'terminal'     => $terminal,
            'shop_orderid' => $orderId,
            'amount'       => $amount,
            'currency'     => $currencyCode,
            'type'         => $paymentType,
        );

        if ($customerInfo !== null && is_array($customerInfo)) {
            $this->addCustomerInfo($customerInfo, $args);
        }

        if ($cookie !== null) {
            $args['cookie'] = $cookie;
        }
        if ($language !== null) {
            $args['language'] = $language;
        }
        if (count($transactionInfo) > 0) {
            $args['transaction_info'] = $transactionInfo;
        }
        if (count($orderLines) > 0) {
            $args['orderLines'] = $orderLines;
        }
        if (in_array($accountOffer, array('required', 'disabled'))) {
            $args['account_offer'] = $accountOffer;
        }
        if ($ccToken !== null) {
            $args['ccToken'] = $ccToken;
        }
        if ($invoiceNumber !== null && is_string($invoiceNumber)) {
            $args['sale_invoice_number'] = $invoiceNumber;
        }
        if ($fraudService !== null) {
            $args['fraud_service'] = $fraudService;
        }
        if ($paymentSource !== null) {
            $args['payment_source'] = $paymentSource;
        } elseif ($paymentSource === null) {
            $args['payment_source'] = 'eCommerce';
        }
        if ($reconciliationIdentifier !== null && $paymentType === 'paymentAndCapture') {
            $args['sale_reconciliation_identifier'] = $reconciliationIdentifier;
        }
        if ($shippingMethod !== null) {
            $args['shipping_method'] = $shippingMethod;
        }
        if ($customerCreatedDate !== null) {
            $args['customer_created_date'] = $customerCreatedDate;
        }
        if ($organizationNumber !== null) {
            $args['organization_number'] = $organizationNumber;
        }
        if ($salesTax !== null) {
            $args['sales_tax'] = $salesTax;
        }

        $args['config'] = $config;

        return new ValitorCreatePaymentRequestResponse($this->callAPIMethod('createPaymentRequest', $args));
    }

    /**
     * @param null  $paymentType
     * @param null  $customerInfo
     * @param array $transactionInfo
     * @param null  $accountNumber
     * @param null  $bankCode
     * @param null  $fraud_service
     * @param null  $paymentSource
     * @param array $orderLines
     * @param null  $organisationNumber
     * @param null  $personalIdentifyNumber
     * @param null  $birthDate
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCreateInvoiceReservationResponse
     */
    public function createInvoiceReservation(
        $terminal,
        $shopOrderId,
        $amount,
        $currencyCode,
        $paymentType = null,
        $customerInfo = null,
        array $transactionInfo = array(),
        $accountNumber = null,
        $bankCode = null,
        $fraud_service = null,
        $paymentSource = null,
        array $orderLines = array(),
        $organisationNumber = null,
        $personalIdentifyNumber = null,
        $birthDate = null
    ) {
        $args = array(
            'terminal'     => $terminal,
            'shop_orderid' => $shopOrderId,
            'amount'       => $amount,
            'currency'     => $currencyCode,
        );

        if ($paymentType !== null) {
            $args['type'] = $paymentType;
        }
        if ($customerInfo !== null && is_array($customerInfo)) {
            $this->addCustomerInfo($customerInfo, $args); // just checks and saves $customerInfo inside $args
        }
        if (count($transactionInfo) > 0) {
            $args['transaction_info'] = $transactionInfo;
        }
        if ($accountNumber !== null) {
            $args['accountNumber'] = $accountNumber;
        }
        if ($bankCode !== null) {
            $args['bankCode'] = $bankCode;
        }
        if ($fraud_service !== null) {
            $args['fraud_service'] = $fraud_service;
        }
        if ($paymentSource !== null) {
            $args['payment_source'] = $paymentSource;
        }
        if (count($orderLines) > 0) {
            $args['orderLines'] = $orderLines;
        }
        if ($organisationNumber !== null) {
            $args['organisationNumber'] = $organisationNumber;
        }
        if ($personalIdentifyNumber !== null) {
            $args['personalIdentifyNumber'] = $personalIdentifyNumber;
        }
        if ($birthDate !== null) {
            $args['birthDate'] = $birthDate;
        }

        return new ValitorCreateInvoiceReservationResponse($this->callAPIMethod('createInvoiceReservation', $args));
    }

    /**
     * @param string                     $terminal
     * @param string                     $shopOrderId
     * @param float                      $amount
     * @param string                     $currencyCode
     * @param string|null                $creditCardToken
     * @param string|null                $pan
     * @param int|null                   $expiryMonth
     * @param int|null                   $expiryYear
     * @param string|null                $cvc
     * @param array                      $transactionInfo
     * @param string|null                $paymentType
     * @param string|null                $paymentSource
     * @param string|null                $fraudService
     * @param float|null                 $surcharge
     * @param string|null                $customerCreatedDate
     * @param string|null                $shippingMethod
     * @param array<string, string>|null $customerInfo
     * @param array                      $orderLines
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorReservationResponse
     */
    public function reservation(
        $terminal,
        $shopOrderId,
        $amount,
        $currencyCode,
        $creditCardToken = null,
        $pan = null,
        $expiryMonth = null,
        $expiryYear = null,
        $cvc = null,
        array $transactionInfo = array(),
        $paymentType = null,
        $paymentSource = null,
        $fraudService = null,
        $surcharge = null,
        $customerCreatedDate = null,
        $shippingMethod = null,
        $customerInfo = null,
        array $orderLines = array()
    ) {
        $args = array(
            'terminal'     => $terminal,
            'shop_orderid' => $shopOrderId,
            'amount'       => $amount,
            'currency'     => $currencyCode,
        );

        if ($creditCardToken !== null) {
            $args['credit_card_token'] = $creditCardToken;
        }
        if ($pan !== null) {
            $args['cardnum'] = $pan;
        }
        if ($expiryMonth !== null) {
            $args['emonth'] = $expiryMonth;
        }
        if ($expiryYear !== null) {
            $args['eyear'] = $expiryYear;
        }
        if ($cvc !== null) {
            $args['cvc'] = $cvc;
        }
        if (count($transactionInfo) > 0) {
            $args['transaction_info'] = $transactionInfo;
        }
        if ($paymentType !== null) {
            $args['type'] = $paymentType;
        }
        if ($paymentSource !== null) {
            $args['payment_source'] = $paymentSource;
        }
        if ($fraudService !== null) {
            $args['fraud_service'] = $fraudService;
        }
        if ($surcharge !== null) {
            $args['surcharge'] = $surcharge;
        }
        if ($customerCreatedDate !== null) {
            $args['customer_created_date'] = $customerCreatedDate;
        }
        if ($shippingMethod !== null) {
            $args['shipping_method'] = $shippingMethod;
        }
        if ($customerInfo !== null && is_array($customerInfo)) {
            $this->addCustomerInfo($customerInfo, $args); // just checks and saves $customerInfo inside $args
        }
        if (count($orderLines) > 0) {
            $args['orderLines'] = $orderLines;
        }

        return new ValitorReservationResponse($this->callAPIMethod('reservation', $args));
    }

    /**
     * @param float|null $amount
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCaptureRecurringResponse
     *
     * @deprecated - use chargeSubscription instead
     */
    public function captureRecurring($subscriptionId, $amount = null)
    {
        return $this->chargeSubscription($subscriptionId, $amount);
    }

    /**
     * @param string     $subscriptionId
     * @param string     $reconciliationIdentifier
     * @param float|null $amount
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCaptureRecurringResponse
     */
    public function chargeSubscriptionWithReconciliationIdentifier($subscriptionId, $reconciliationIdentifier, $amount = null)
    {
        $this->checkConnection();

        return new ValitorCaptureRecurringResponse(
            $this->callAPIMethod(
                'chargeSubscription',
                array(
                    'transaction_id'            => $subscriptionId,
                    'amount'                    => $amount,
                    'reconciliation_identifier' => $reconciliationIdentifier,
                )
            )
        );
    }

    /**
     * @param string     $subscriptionId
     * @param float|null $amount
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCaptureRecurringResponse
     */
    public function chargeSubscription($subscriptionId, $amount = null)
    {
        return $this->chargeSubscriptionWithReconciliationIdentifier($subscriptionId, null, $amount);
    }

    /**
     * @param float|null $amount
     *
     * @throws ValitorMerchantAPIException
     *
     * @return ValitorPreauthRecurringResponse
     *
     * @deprecated - use reserveSubscriptionCharge instead
     */
    public function preauthRecurring($subscriptionId, $amount = null)
    {
        return $this->reserveSubscriptionCharge($subscriptionId, $amount);
    }

    /**
     * @param float|null $amount
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorPreauthRecurringResponse
     */
    public function reserveSubscriptionCharge($subscriptionId, $amount = null)
    {
        $this->checkConnection();

        return new ValitorPreauthRecurringResponse(
            $this->callAPIMethod(
                'reserveSubscriptionCharge',
                array(
                    'transaction_id' => $subscriptionId,
                    'amount'         => $amount,
                )
            )
        );
    }

    /**
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCalculateSurchargeResponse
     */
    public function calculateSurcharge($terminal, $cardToken, $amount, $currency)
    {
        $this->checkConnection();

        return new ValitorCalculateSurchargeResponse(
            $this->callAPIMethod(
                'calculateSurcharge',
                array(
                    'terminal'          => $terminal,
                    'credit_card_token' => $cardToken,
                    'amount'            => $amount,
                    'currency'          => $currency,
                )
            )
        );
    }

    /**
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return ValitorCalculateSurchargeResponse
     */
    public function calculateSurchargeForSubscription($subscriptionId, $amount)
    {
        $this->checkConnection();

        return new ValitorCalculateSurchargeResponse(
            $this->callAPIMethod(
                'calculateSurcharge',
                array(
                    'payment_id' => $subscriptionId,
                    'amount'     => $amount,
                )
            )
        );
    }

    /**
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return bool|string
     */
    public function getCustomReport($args)
    {
        $this->checkConnection();
        $response = $this->callAPIMethod('getCustomReport', $args);
        return $response;
    }

    /**
     * @param ValitorAPITransactionsRequest $transactionsRequest
     *
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     *
     * @return bool|string
     */
    public function getTransactions(ValitorAPITransactionsRequest $transactionsRequest)
    {
        $this->checkConnection();
        return $this->callAPIMethod('transactions', $transactionsRequest->asArray());
    }

    /**
     * @param array $customerInfo
     * @param array $args
     *
     * @throws ValitorMerchantAPIException
     *
     * @return void
     */
    private function addCustomerInfo($customerInfo, &$args)
    {
        $errors = array();
        $sessionId = session_id();
        //Check if customer IP address is forwarded by a transparent proxy, then set it in customer info
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $customerInfo['client_forwarded_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $customerInfo['client_accept_language'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        $customerInfo['client_session_id'] = md5($sessionId);
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $customerInfo['client_ip'] = $_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $customerInfo['client_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }
        foreach ($customerInfo as $customerInfoKey => $customerInfoValue) {
            if (is_array($customerInfo[$customerInfoKey])) {
                $errors[] = "customer_info[$customerInfoKey] is not expected to be an array";
            }
        }
        if (count($errors) > 0) {
            throw new ValitorMerchantAPIException("Failed to create customer_info variable: \n".print_r($errors, true));
        }
        $args['customer_info'] = $customerInfo;
    }
}
