<?php

/**
 * Class ValitorGetPaymentResponse.
 */
class ValitorGetPaymentResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorGetPaymentResponse constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXMLElement $body
     *
     * @return void
     */
    protected function parseBody(SimpleXMLElement $body)
    {
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && $this->getPrimaryPayment() !== null;
    }
}
