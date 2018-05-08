<?php

class PensioAPIChargebackEvents
{
	private $simpleXmlElement;
	private $chargebackEvents = array();

	public function __construct(SimpleXmlElement $xml)
	{
		$this->simpleXmlElement = $xml;
		if(isset($xml->ChargebackEvent))
		{
			foreach($xml->ChargebackEvent as $chargebackEvent)
			{
				$this->chargebackEvents[] = new PensioAPIChargebackEvent($chargebackEvent);
			}
		}
	}

	/**
	 * @return PensioAPIChargebackEvent
	 */
	public function getNewest()
	{
		$newest = null; /* @var $newest PensioAPIChargebackEvent */
		foreach($this->chargebackEvents as $chargebackEvent) /* @var $chargebackEvent PensioAPIChargebackEvent */
		{
			if(is_null($newest) || $newest->getDate()->getTimestamp() < $chargebackEvent->getDate()->getTimestamp())
			{
				$newest = $chargebackEvent;
			}
		}

		return $newest;
	}

	/**
	 * @return SimpleXMLElement an XML representation of the object as it was instantiated
	 */
	public function getXmlElement()
	{
		return $this->simpleXmlElement;
	}
}