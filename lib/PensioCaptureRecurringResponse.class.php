<?php

require_once(dirname(__FILE__).'/PensioAbstractPaymentResponse.class.php');

class PensioCaptureRecurringResponse extends PensioAbstractPaymentResponse
{
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
	}
	
	protected function parseBody(SimpleXmlElement $body)
	{
		
	}

	/**
	 * @return PensioAPIPayment
	 */
	public function getSubscriptionPayment()
	{
		return $this->payments[0];
	}
	
	/**
	 * @return PensioAPIPayment
	 */
	public function getPrimaryPayment()
	{
		return $this->payments[1];
	}
	
	/**
	 * @return boolean
	 */
	public function wasSubscriptionReleased()
	{
		return $this->getSubscriptionPayment()->isReleased();
	}
}