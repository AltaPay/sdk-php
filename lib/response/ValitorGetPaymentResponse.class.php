<?php

/**
 * Class ValitorGetPaymentResponse
 */
class ValitorGetPaymentResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorGetPaymentResponse constructor.
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXmlElement $body
     */
    protected function parseBody(SimpleXmlElement $body)
    {
        
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && !is_null($this->getPrimaryPayment());
    }


}