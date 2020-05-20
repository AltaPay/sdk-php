<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractPaymentResponse.class.php';

class ValitorCaptureResponse extends ValitorAbstractPaymentResponse
{
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }
    
    protected function parseBody(SimpleXmlElement $body)
    {
        
    }
    
}
