<?php

require_once(dirname(__FILE__).'/PensioAbstractResponse.class.php');
require_once(dirname(__FILE__).'/PensioAPIPayment.class.php');

abstract class PensioAbstractPaymentResponse extends PensioAbstractResponse
{
	private $result;
	private $merchantErrorMessage, $cardHolderErrorMessage;
	protected $payments = array();
	
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
		$this->initFromXml($xml);
	}
	
	public function __wakeup()
	{
		$this->initFromXml(new SimpleXmlElement($this->xml));
	}
	
	private function initFromXml(SimpleXmlElement $xml)
	{
		$this->payments = array();
		if($this->getErrorCode() === '0')
		{
			$this->result = (string)$xml->Body->Result;
			$this->merchantErrorMessage = (string)$xml->Body->MerchantErrorMessage;
			$this->cardHolderErrorMessage = (string)$xml->Body->CardHolderErrorMessage;
			
			$this->parseBody($xml->Body);
			
			foreach($xml->Body->Transactions->Transaction as $transactionXml)
			{
				$this->addPayment(new PensioAPIPayment($transactionXml));
			}
		}
	}
	
	private function addPayment(PensioAPIPayment $payment)
	{
		$this->payments[] = $payment;
	}
	
	/**
	 * @return array of PensioAPIPayment
	 */
	public function getPayments()
	{
		return $this->payments;
	}
	
	/**
	 * @return PensioAPIPayment
	 */
	public function getPrimaryPayment()
	{
		return $this->payments[0];
	}
	
	public function wasSuccessful()
	{
		return $this->getErrorCode() === '0' && $this->result == 'Success';
	}
	
	public function wasDeclined()
	{
		return $this->getErrorCode() === '0' && $this->result == 'Failed';
	}
	
	public function getMerchantErrorMessage()
	{
		return $this->merchantErrorMessage;
	}
	
	public function getCardHolderErrorMessage()
	{
		return $this->cardHolderErrorMessage;
	}
	
	abstract protected function parseBody(SimpleXmlElement $body);
}