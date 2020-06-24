<?php

class ValitorCaptureRecurringResponse extends ValitorPreauthRecurringResponse
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