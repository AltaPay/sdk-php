<?php

require_once(dirname(__FILE__).'/PensioAPIReconciliationIdentifier.class.php');

/**
   [Transaction] =&gt; SimpleXMLElement Object
       (
           [TransactionId] =&gt; 5
           [AuthType] =&gt; payment
           [CardStatus] =&gt; Valid
           [CreditCardToken] =&gt; ce657182528301c19032840ba6682bdeb5b342d8
           [CreditCardMaskedPan] =&gt; 555555*****5444
           [ThreeDSecureResult] =&gt; Not_Attempted
           [BlacklistToken] =&gt; 9484bac14dfd5dbb27329f81dcb12ceb8ed7703e
           [ShopOrderId] =&gt; qoute_247
           [Shop] =&gt; Pensio Functional Test Shop
           [Terminal] =&gt; Pensio Dev Terminal
           [TransactionStatus] =&gt; preauth
           [MerchantCurrency] =&gt; 978
           [CardHolderCurrency] =&gt; 978
           [ReservedAmount] =&gt; 14.10
           [CapturedAmount] =&gt; 0
           [RefundedAmount] =&gt; 0
           [RecurringMaxAmount] =&gt; 0
           [CreatedDate] =&gt; 2012-01-06 15:23:12
           [UpdatedDate] =&gt; 2012-01-06 15:23:12
           [PaymentNature] =&gt; CreditCard
           [PaymentNatureService] =&gt; SimpleXMLElement Object
               (
                   [@attributes] =&gt; Array
                       (
                           [name] =&gt; TestAcquirer
                       )

                   [SupportsRefunds] =&gt; true
                   [SupportsRelease] =&gt; true
                   [SupportsMultipleCaptures] =&gt; true
                   [SupportsMultipleRefunds] =&gt; true
               )

           [FraudRiskScore] =&gt; 14
           [FraudExplanation] =&gt; For the test fraud service the risk score is always equal mod 101 of the created amount for the payment
           [TransactionInfo] =&gt; SimpleXMLElement Object
               (
               )

           [CustomerInfo] =&gt; SimpleXMLElement Object
               (
                   [UserAgent] =&gt; SimpleXMLElement Object
                       (
                       )

                   [IpAddress] =&gt; 127.0.0.1
               )

           [ReconciliationIdentifiers] =&gt; SimpleXMLElement Object
               (
               )
 * @author emanuel
 */
class PensioAPIPayment
{
	private $paymentId;
	private $authType;
	private $creditCardMaskedPan;
	private $creditCardToken;
	private $cardStatus;
	private $shopOrderId;
	private $shop;
	private $terminal;
	private $transactionStatus;
	private $currency;
	
	private $reservedAmount;
	private $capturedAmount;
	private $refundedAmount;
	private $recurringMaxAmount;
		
	private $paymentNature;
	private $paymentNatureService;
	
	private $reconciliationIdentifiers = array();
	
	public function __construct(SimpleXmlElement $xml)
	{
		$this->paymentId = (string)$xml->TransactionId;
		$this->authType = (string)$xml->AuthType;
		$this->creditCardMaskedPan = (string)$xml->CreditCardMaskedPan;
		$this->creditCardToken = (string)$xml->CreditCardToken;
		$this->cardStatus = (string)$xml->CardStatus;
		$this->shopOrderId = (string)$xml->ShopOrderId;
		$this->shop = (string)$xml->Shop;
		$this->terminal = (string)$xml->Terminal;
		$this->transactionStatus = (string)$xml->TransactionStatus;
		$this->currency = (string)$xml->MerchantCurrency;
		
		$this->reservedAmount = (string)$xml->ReservedAmount;
		$this->capturedAmount = (string)$xml->CapturedAmount;
		$this->refundedAmount = (string)$xml->RefundedAmount;
		$this->recurringMaxAmount = (string)$xml->RecurringMaxAmount;
		
		$this->paymentNature = (string)$xml->PaymentNature;
		$this->paymentNatureService = new PensioAPIPaymentNatureService($xml->PaymentNatureService);
		
		foreach($xml->ReconciliationIdentifiers->ReconciliationIdentifier as $reconXml)
		{
			$this->reconciliationIdentifiers[] = new PensioAPIReconciliationIdentifier($reconXml);
		}
		
	}
	
	public function mustBeCaptured()
	{
		return $this->capturedAmount == '0';
	}
	
	public function getCurrentStatus()
	{
		return $this->transactionStatus;
	}
	
	public function isReleased()
	{
		return $this->getCurrentStatus() == 'released';
	}
	
	/**
	 * @return PensioAPIReconciliationIdentifier
	 */
	public function getLastReconciliationIdentifier()
	{
		return $this->reconciliationIdentifiers[count($this->reconciliationIdentifiers) - 1];
	}
	
	public function getId()
	{
		return $this->paymentId;
	}
	
	public function getAuthType()
	{
		return $this->authType;
	}
	
	public function getShopOrderId()
	{
		return $this->shopOrderId;
	}
	
	public function getMaskedPan()
	{
		return $this->creditCardMaskedPan;
	}
	
	public function getCreditCardToken()
	{
		return $this->creditCardToken;
	}
	
	public function getCardStatus()
	{
		return $this->cardStatus;
	}
	
	public function getPaymentNature()
	{
		return $this->paymentNature;
	}
	
	public function getPaymentNatureService()
	{
		return $this->paymentNatureService;
	}
	
	public function getCurrency()
	{
		return $this->currency;
	}
	
	public function getReservedAmount()
	{
		return $this->reservedAmount;
	}
	
	public function getCapturedAmount()
	{
		return $this->capturedAmount; 
	}
}