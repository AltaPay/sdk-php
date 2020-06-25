<?php

class ValitorCalculateSurchargeResponse extends ValitorAbstractResponse
{
    /** @var string */
    private $result;
    /** @var string */
    private $surchargeAmount;

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);

        if ($this->getErrorCode() === '0') {
            $this->result = (string)$xml->Body->Result;
            $this->surchargeAmount = (string)$xml->Body->SurchageAmount;
        }
    }

    /**
     * @return string
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
