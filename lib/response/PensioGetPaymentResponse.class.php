<?php

require_once(dirname(__FILE__).'/PensioAbstractPaymentResponse.class.php');

class PensioGetPaymentResponse extends PensioAbstractPaymentResponse
{
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
	}
	
	protected function parseBody(SimpleXmlElement $body)
	{
		
	}

	public function wasSuccessful()
	{
		return $this->getErrorCode() === '0' && !is_null($this->getPrimaryPayment());
	}


}