<?php

require_once(dirname(__FILE__).'/PensioAbstractResponse.class.php');
require_once(dirname(__FILE__).'/PensioTerminal.class.php');

class PensioCalculateSurchargeResponse extends PensioAbstractResponse
{
	private $result;
	private $surchargeAmount = array();
	
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
		
		if($this->getErrorCode() === '0')
		{
			$this->result = (string)$xml->Body->Result;
			$this->surchargeAmount = (string)$xml->Body->SurchageAmount;
		}
	}
	
	public function getSurchargeAmount()
	{
		return $this->surchargeAmount;
	}
	
	public function wasSuccessful()
	{
		return $this->result === 'Success';
	}
}