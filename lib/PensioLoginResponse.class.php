<?php

require_once(dirname(__FILE__).'/PensioAbstractResponse.class.php');

class PensioLoginResponse extends PensioAbstractResponse
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
		return $this->getErrorCode() === '0' && $this->result == 'OK';
	}	
}