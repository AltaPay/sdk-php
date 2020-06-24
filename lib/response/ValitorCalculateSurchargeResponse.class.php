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
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
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
