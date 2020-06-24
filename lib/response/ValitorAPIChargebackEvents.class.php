<?php

/**
 * Class ValitorAPIChargebackEvents.
 */
class ValitorAPIChargebackEvents
{
    private $simpleXmlElement;
    /** @var ValitorAPIChargebackEvent[] */
    private $chargebackEvents = array();

    /**
     * ValitorAPIChargebackEvents constructor.
     *
     * @param SimpleXmlElement $xml
     *
     * @throws Exception
     */
    public function __construct(SimpleXmlElement $xml)
    {
        $this->simpleXmlElement = $xml;
        if (isset($xml->ChargebackEvent)) {
            foreach ($xml->ChargebackEvent as $chargebackEvent) {
                $this->chargebackEvents[] = new ValitorAPIChargebackEvent($chargebackEvent);
            }
        }
    }

    /**
     * @return ValitorAPIChargebackEvent
     */
    public function getNewest()
    {
        $newest = null;
        foreach ($this->chargebackEvents as $chargebackEvent)
        {
            if ($newest === null || $newest->getDate()->getTimestamp() < $chargebackEvent->getDate()->getTimestamp()) {
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
