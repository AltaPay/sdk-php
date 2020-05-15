<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractResponse.class.php';
require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorTerminal.class.php';

/**
 * Class ValitorGetTerminalsResponse
 */
class ValitorGetTerminalsResponse extends ValitorAbstractResponse
{
    private $terminals = array();
    
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
        
        if($this->getErrorCode() === '0') {
            foreach($xml->Body->Terminals->Terminal as $terminalXml)
            {
                $terminal = new ValitorTerminal();
                $terminal->setTitle((string)$terminalXml->Title);
                $terminal->setCountry((string)$terminalXml->Country);
                foreach($terminalXml->Natures->Nature as $nature)
                {
                    $terminal->addNature((string)$nature);
                }
                foreach($terminalXml->Currencies->Currency as $currency)
                {
                    $terminal->addCurrency((string)$currency);
                }
                
                $this->terminals[] = $terminal;
            }
        }
    }

    /**
     * @return array
     */
    public function getTerminals()
    {
        return $this->terminals;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0';
    }
}