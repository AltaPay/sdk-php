<?php

require_once(dirname(__FILE__).'/IPensioCommunicationLogger.class.php');
require_once(dirname(__FILE__).'/PensioGetTerminalsResponse.class.php');
require_once(dirname(__FILE__).'/PensioLoginResponse.class.php');
require_once(dirname(__FILE__).'/PensioCreatePaymentRequestResponse.class.php');
require_once(dirname(__FILE__).'/PensioCaptureResponse.class.php');
require_once(dirname(__FILE__).'/PensioRefundResponse.class.php');
require_once(dirname(__FILE__).'/PensioReleaseResponse.class.php');
require_once(dirname(__FILE__).'/PensioReservationResponse.class.php');
require_once(dirname(__FILE__).'/PensioCaptureRecurringResponse.class.php');
require_once(dirname(__FILE__).'/PensioPreauthRecurringResponse.class.php');
require_once(dirname(__FILE__).'/PensioAPIPaymentNatureService.class.php');
require_once(dirname(__FILE__).'/PensioAPICustomerInfo.class.php');
require_once(dirname(__FILE__).'/PensioAPIAddress.class.php');
require_once(dirname(__FILE__).'/PensioAPIPaymentInfos.class.php');
require_once(dirname(__FILE__).'/PensioAPIFunding.class.php');
require_once(dirname(__FILE__).'/PensioCalculateSurchargeResponse.class.php');
require_once(dirname(__FILE__).'/PensioFundingListResponse.class.php');
require_once(dirname(__FILE__).'/http/PensioFOpenBasedHttpUtils.class.php');
require_once(dirname(__FILE__).'/http/PensioCurlBasedHttpUtils.class.php');

class PensioMerchantAPI
{
	private $baseURL, $username, $password;
	private $connected = false;
	/**
	 * @var IPensioCommunicationLogger
	 */
	private $logger;
	private $httpUtil;

	public function __construct($baseURL, $username, $password, IPensioCommunicationLogger $logger = null, IPensioHttpUtils $httpUtil = null)
	{
		$this->connected = false;
		$this->baseURL = rtrim($baseURL, '/');
		$this->username = $username;
		$this->password = $password;
		$this->logger = $logger;
		
		if(is_null($httpUtil))
		{
			if(function_exists('curl_init'))
			{
				$httpUtil = new PensioCurlBasedHttpUtils();
			}
			else if(ini_get('allow_url_fopen'))
			{
				$httpUtil = new PensioFOpenBasedHttpUtils();
			}
			else
			{
				throw new Exception("Neither allow_url_fopen nor cURL is installed, we cannot communicate with Pensio's Payment Gateway without at least one of them.");
			}
		}
		$this->httpUtil = $httpUtil;
	}

	private function checkConnection()
	{
		if(!$this->connected)
		{
			throw new Exception("Not Connected, invoke connect(...) before using any API calls");
		}
	}
	
	public function isConnected()
	{
		return $this->connected;
	}

	private function maskPan($pan)
	{
		if(strlen($pan) >= 10)
		{
			return  substr($pan, 0, 6).str_repeat('x', strlen($pan) - 10).substr($pan, -4);
		}
		else
		{
			return $pan;
		}
	}

	private function callAPIMethod($method, array $args = array())
	{
		if(!is_null($this->logger))
		{
			$loggedArgs = $args;
			if(isset($loggedArgs['cardnum']))
			{
				$loggedArgs['cardnum'] = $this->maskPan($loggedArgs['cardnum']);
			}
			if(isset($loggedArgs['cvc']))
			{
				$loggedArgs['cvc'] = str_repeat('x', strlen($loggedArgs['cvc']));
			}
			$logId = $this->logger->logRequest($this->baseURL."/merchant/API/".$method.'?'.http_build_query($loggedArgs));
		}

		$request = new PensioHttpRequest();
		$request->setUrl($this->baseURL."/merchant/API/".$method);
		$request->setParameters($args);
		$request->setUser($this->username);
		$request->setPass($this->password);
		$request->setMethod('POST');
		
		$response = $this->httpUtil->requestURL($request);
		
		if(!is_null($this->logger))
		{
			$this->logger->logResponse($logId, print_r($response, true));
		}

		try
		{
			if($response->getHttpCode() == 200 && stripos($response->getContentType(), "text/xml") !== false)
			{
				return new SimpleXMLElement($response->getContent());
			}
			else if(stripos($response->getContentType(), "text/xml") === false)
			{
				return new SimpleXMLElement('<APIResponse version="unknown">'
					.'<Header>'
					.'<Date>'.date('c').'</Date>'
					.'<Path>API/'.$method.'</Path>'
					.'<ErrorCode>400</ErrorCode>'
					.'<ErrorMessage>Cannot understand answer from the server</ErrorMessage>'
					.'</Header>'
					.'</APIResponse>'
				);
			}
			else if($response->getHttpCode() == 401)
			{
				return new SimpleXMLElement('<APIResponse version="unknown">'
					.'<Header>'
					.'<Date>'.date('c').'</Date>'
					.'<Path>API/'.$method.'</Path>'
					.'<ErrorCode>401</ErrorCode>'
					.'<ErrorMessage>Unauthorized Access Denied</ErrorMessage>'
					.'</Header>'
					.'</APIResponse>'
				);
			}
			else
			{
				return new SimpleXMLElement('<APIResponse version="unknown">'
					.'<Header>'
					.'<Date>'.date('c').'</Date>'
					.'<Path>API/'.$method.'</Path>'
					.'<ErrorCode>'.$response->getHttpCode().'</ErrorCode>'
					.'<ErrorMessage>Unknown error</ErrorMessage>'
					.'</Header>'
					.'</APIResponse>'
				);
			}
		}
		catch(Exception $e)
		{
			return new SimpleXMLElement('<APIResponse version="unknown">'
				.'<Header>'
				.'<Date>'.date('c').'</Date>'
				.'<Path>API/'.$method.'</Path>'
				.'<ErrorCode>89174</ErrorCode>'
				.'<ErrorMessage>Error: '.$e->getMessage().'</ErrorMessage>'
				.'</Header>'
				.'</APIResponse>'
			);
		}
		return new SimpleXMLElement('<APIResponse version="unknown">'
			.'<Header>'
			.'<Date>'.date('c').'</Date>'
			.'<Path>API/'.$method.'</Path>'
			.'<ErrorCode>239874</ErrorCode>'
			.'<ErrorMessage>Unknown Error</ErrorMessage>'
			.'</Header>'
			.'</APIResponse>'
		);
	}

	/**
	 * @return PensioFundingListResponse
	 */
	public function getFundingList($page=1)
	{
		$this->checkConnection();

		return new PensioFundingListResponse($this->callAPIMethod('fundingList', array('page'=>$page)));
	}
	
	public function downloadFundingCSV(PensioAPIFunding $funding)
	{
		$this->checkConnection();

		$request = new PensioHttpRequest();
		$request->setUrl($funding->getDownloadLink());
		$request->setUser($this->username);
		$request->setPass($this->password);
		$request->setMethod('GET');
		
		$response = $this->httpUtil->requestURL($request);
		
		if($response->getHttpCode() == 200)
		{
			return $response->getContent();
		}
		
		return false;
	}
	
	private function reservationInternal(
			  $apiMethod
			, $terminal
			, $shop_orderid
			, $amount
			, $currency
			, $cc_num
			, $cc_expiry_year
			, $cc_expiry_month
			, $credit_card_token
			, $cvc
			, $type
			, $payment_source
			, array $customerInfo
			, array $transaction_info)
	{
		$this->checkConnection();
	
		$args = array(
				'terminal'=>$terminal,
				'shop_orderid'=>$shop_orderid,
				'amount'=>$amount,
				'currency'=>$currency,
				'cvc'=>$cvc,
				'type'=>$type,
				'payment_source'=>$payment_source
		);
		if(!is_null($credit_card_token))
		{
			$args['credit_card_token'] = $credit_card_token;
		}
		else
		{
			$args['cardnum'] = $cc_num;
			$args['emonth'] = $cc_expiry_month;
			$args['eyear'] = $cc_expiry_year;
		}
		foreach(array('billing_city', 'billing_region', 'billing_postal', 'billing_country', 'email', 'customer_phone', 'bank_name', 'bank_phone', 'billing_firstname', 'billing_lastname', 'billing_address') as $custField)
		{
			if(isset($customerInfo[$custField]))
			{
				$args[$custField] = $customerInfo[$custField];
			}
		}
		if(count($transaction_info) > 0)
		{
			$args['transaction_info'] = $transaction_info;
		}
	
		return new PensioReservationResponse(
				$this->callAPIMethod(
						$apiMethod,
						$args
				)
		);
	}
	

	public function reservationOfFixedAmount(
		  $terminal
		, $shop_orderid
		, $amount
		, $currency
		, $cc_num
		, $cc_expiry_year
		, $cc_expiry_month
		, $cvc
		, $payment_source
		, array $customerInfo = array()
		, array $transactionInfo = array())
	{
		return $this->reservationInternal(
				'reservationOfFixedAmountMOTO'
				, $terminal
				, $shop_orderid
				, $amount
				, $currency
				, $cc_num
				, $cc_expiry_year
				, $cc_expiry_month
				, null // $credit_card_token
				, $cvc
				, 'payment'
				, $payment_source
				, $customerInfo
				, $transactionInfo);
	}

	public function reservationOfFixedAmountMOTOWithToken(
		$terminal
		, $shop_orderid
		, $amount
		, $currency
		, $credit_card_token
		, $cvc = null
		, $payment_source = 'moto'
		, array $customerInfo = array()
		, array $transactionInfo = array())
	{
		return $this->reservationInternal(
				'reservationOfFixedAmountMOTO'
				, $terminal
				, $shop_orderid
				, $amount
				, $currency
				, null
				, null
				, null
				, $credit_card_token
				, $cvc
				, 'payment'
				, $payment_source
				, $customerInfo
				, $transactionInfo);
	}

	public function setupSubscription(
		$terminal
		, $shop_orderid
		, $amount
		, $currency
		, $cc_num
		, $cc_expiry_year
		, $cc_expiry_month
		, $cvc
		, $payment_source
		, array $customerInfo = array()
		, array $transactionInfo = array())
	{
		return $this->reservationInternal(
				'setupSubscription'
				, $terminal
				, $shop_orderid
				, $amount
				, $currency
				, $cc_num
				, $cc_expiry_year
				, $cc_expiry_month
				, null // $credit_card_token
				, $cvc
				, 'subscription'
				, $payment_source
				, $customerInfo
				, $transactionInfo);		
	}
	
	public function verifyCard(
		$terminal
		, $shop_orderid
		, $currency
		, $cc_num
		, $cc_expiry_year
		, $cc_expiry_month
		, $cvc
		, $payment_source
		, array $customerInfo = array()
		, array $transactionInfo = array())
	{
		return $this->reservationInternal(
				'reservationOfFixedAmountMOTO'
				, $terminal
				, $shop_orderid
				, 1.00
				, $currency
				, $cc_num
				, $cc_expiry_year
				, $cc_expiry_month
				, null // $credit_card_token
				, $cvc
				, 'verifyCard'
				, $payment_source
				, $customerInfo
				, $transactionInfo);		
	}
	
	
	/**
	 * @return PensioCaptureResponse
	 */
	public function captureReservation($paymentId, $amount=null, array $orderLines=array(), $salesTax=null)
	{
		$this->checkConnection();

		return new PensioCaptureResponse(
			$this->callAPIMethod(
				'captureReservation',
				array(
					'transaction_id'=>$paymentId, 
					'amount'=>$amount,
					'orderLines'=>$orderLines,
					'sales_tax'=>$salesTax,
				)
			)
		);
	}

	/**
	 * @return PensioRefundResponse
	 */
	public function refundCapturedReservation($paymentId, $amount=null)
	{
		$this->checkConnection();

		return new PensioRefundResponse(
			$this->callAPIMethod(
				'refundCapturedReservation',
				array(
					'transaction_id'=>$paymentId, 
					'amount'=>$amount
				)
			)
		);
	}

	public function releaseReservation($paymentId, $amount=null)
	{
		$this->checkConnection();

		return new PensioReleaseResponse(
			$this->callAPIMethod(
				'releaseReservation',
				array(
					'transaction_id'=>$paymentId
				)
			)
		);
	}

	public function getPayment($paymentId)
	{
		$this->checkConnection();

		$body = $this->callAPIMethod(
			'payments',
			array(
				'transaction'=>$paymentId
			)
		);

		if(isset($body->Body->Transactions))
		{
			return $body->Body->Transactions->Transaction;
		}
		return null;
	}
	
	/**
	 * @return PensioGetTerminalsResponse
	 */
	public function getTerminals()
	{
		$this->checkConnection();

		return new PensioGetTerminalsResponse($this->callAPIMethod('getTerminals'));
	}

	/**
	 * @return PensioLoginResponse
	 */
	public function login()
	{
		$this->connected = false;
		
		$response = new PensioLoginResponse($this->callAPIMethod('login'));
		
		if($response->getErrorCode() === '0')
		{
			$this->connected = true;
		}
		
		return $response;
	}
	
	/**
	 * @return PensioCreatePaymentRequestResponse
	 */
	public function createPaymentRequest($terminal,
			$orderid,
			$amount,
			$currencyCode,
			$paymentType,
			$customerInfo = null,
			$cookie = null,
			$language = null,
			array $config = array(),
			array $transaction_info = array())
	{
		$args = array(
			'terminal'=>$terminal,
			'shop_orderid'=>$orderid,
			'amount'=>$amount,
			'currency'=>$currencyCode,
			'type'=>$paymentType
		);
		if(!is_null($customerInfo))
		{
			$args['customer_info'] = $customerInfo;
		}
		if(!is_null($cookie))
		{
			$args['cookie'] = $cookie;
		}  
		if(!is_null($language))
		{
			$args['language'] = $language;
		}
		if(count($transaction_info) > 0)
		{
			$args['transaction_info'] = $transaction_info;
		}	
		$args['config'] = $config;
		
		return new PensioCreatePaymentRequestResponse($this->callAPIMethod('createPaymentRequest', $args));
	}
	
	/**
	 * @return PensioCaptureRecurringResponse
	 * @deprecated - use chargeSubscription instead.
	 */
	public function captureRecurring($subscriptionId, $amount=null)
	{
		return $this->chargeSubscription($subscriptionId, $amount);
	}	
		
	/**
	 * @return PensioCaptureRecurringResponse
	 */
	public function chargeSubscription($subscriptionId, $amount=null)
	{
		$this->checkConnection();

		return new PensioCaptureRecurringResponse(
			$this->callAPIMethod(
				'chargeSubscription',
				array(
					'transaction_id'=>$subscriptionId, 
					'amount'=>$amount,
				)
			)
		);
	}
	
	/**
	 * @return PensioPreauthRecurringResponse
	 * @deprecated - use reserveSubscriptionCharge instead
	 */
	public function preauthRecurring($subscriptionId, $amount=null)
	{
		return $this->reserveSubscriptionCharge($subscriptionId, $amount);
	}
	
	
	/**
	 * @return PensioPreauthRecurringResponse
	 */
	public function reserveSubscriptionCharge($subscriptionId, $amount=null)
	{
		$this->checkConnection();

		return new PensioPreauthRecurringResponse(
			$this->callAPIMethod(
				'reserveSubscriptionCharge',
				array(
					'transaction_id'=>$subscriptionId, 
					'amount'=>$amount,
				)
			)
		);
	}

	/**
	 * @return PensioCalculateSurchargeResponse
	 */
	public function calculateSurcharge($terminal, $cardToken, $amount, $currency)
	{
		$this->checkConnection();
	
		return new PensioCalculateSurchargeResponse(
				$this->callAPIMethod(
						'calculateSurcharge',
						array(
								'terminal'=>$terminal,
								'credit_card_token'=>$cardToken,
								'amount'=>$amount,
								'currency'=>$currency,
						)
				)
		);
	}
	
	/**
	 * @return PensioCalculateSurchargeResponse
	 */
	public function calculateSurchargeForSubscription($subscriptionId, $amount)
	{
		$this->checkConnection();
	
		return new PensioCalculateSurchargeResponse(
				$this->callAPIMethod(
						'calculateSurcharge',
						array(
								'payment_id'=>$subscriptionId,
								'amount'=>$amount,
						)
				)
		);
	}
}
