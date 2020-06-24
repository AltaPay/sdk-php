<?php

/**
 * Class ValitorLoginResponse
 */
class ValitorLoginResponse extends ValitorAbstractResponse
{
    private $result;

    /**
     * ValitorLoginResponse constructor.
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
        if($this->getErrorCode() === '0') {
            $this->result = (string)$xml->Body->Result;
        }
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && $this->result == 'OK';
    }    
}