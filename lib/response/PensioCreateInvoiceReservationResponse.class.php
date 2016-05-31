<?php

if(!defined('PENSIO_API_ROOT'))
{
	define('PENSIO_API_ROOT',dirname(__DIR__));
}

require_once(PENSIO_API_ROOT.'/response/PensioAbstractResponse.class.php');

class PensioCreateInvoiceReservationResponse extends PensioAbstractResponse
{
	private $result;
	
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
		
		if($this->getErrorCode() === '0')
		{
			$this->result = (string)$xml->Body->Result;
		}
	}
	
	public function wasSuccessful()
	{
		return $this->getErrorCode() === '0' && $this->result == 'Success';
	}
}