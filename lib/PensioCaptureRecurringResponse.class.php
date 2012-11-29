<?php

require_once(dirname(__FILE__).'/PensioPreauthRecurringResponse.class.php');

class PensioCaptureRecurringResponse extends PensioPreauthRecurringResponse
{
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
	}
	
	/**
	 * @return boolean
	 */
	public function wasSubscriptionReleased()
	{
		return $this->getSubscriptionPayment()->isReleased();
	}
}