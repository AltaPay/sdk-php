<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractPaymentResponse.class.php';

class ValitorRefundResponse extends ValitorAbstractPaymentResponse
{
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXmlElement $body
     * @return mixed|void
     */
    protected function parseBody(SimpleXmlElement $body)
    {
        
    }
}