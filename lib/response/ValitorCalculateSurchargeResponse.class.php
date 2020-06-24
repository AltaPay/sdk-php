<?php

/**
 * Class ValitorCalculateSurchargeResponse.
 */
class ValitorCalculateSurchargeResponse extends ValitorAbstractResponse
{
    private $result;
    private $surchargeAmount = array();

    /**
     * ValitorCalculateSurchargeResponse constructor.
     *
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
