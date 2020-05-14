<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorPreauthRecurringResponse.class.php';

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