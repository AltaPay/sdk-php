<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractPaymentResponse.class.php';

class ValitorReservationResponse extends ValitorAbstractPaymentResponse
{

    /**
     * ValitorReservationResponse constructor.
     * @param SimpleXmlElement $xml
     */
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