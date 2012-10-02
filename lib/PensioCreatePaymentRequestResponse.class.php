<?php

require_once(dirname(__FILE__).'/PensioAbstractResponse.class.php');

class PensioCreatePaymentRequestResponse extends PensioAbstractResponse
{
	private $redirectURL, $result;
	
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
		
		if($this->getErrorCode() === '0')
		{
			$this->result = (string)$xml->Body->Result;
			$this->redirectURL = (string)$xml->Body->Url;
		}
	}
	
	public function getRedirectURL()
	{
		return $this->redirectURL;
	}
	
	public function wasSuccessful()
	{
		return $this->getErrorCode() === '0' && $this->result == 'Success';
	}
}