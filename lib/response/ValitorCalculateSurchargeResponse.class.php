<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractResponse.class.php';
require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorTerminal.class.php';

/**
 * Class ValitorCalculateSurchargeResponse
 */
class ValitorCalculateSurchargeResponse extends ValitorAbstractResponse
{
    private $result;
    private $surchargeAmount = array();

    /**
     * ValitorCalculateSurchargeResponse constructor.
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
        
        if($this->getErrorCode() === '0') {
            $this->result = (string)$xml->Body->Result;
            $this->surchargeAmount = (string)$xml->Body->SurchageAmount;
        }
    }

    /**
     * @return array|string
     */
    public function getSurchargeAmount()
    {
        return $this->surchargeAmount;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->result === 'Success';
    }
}