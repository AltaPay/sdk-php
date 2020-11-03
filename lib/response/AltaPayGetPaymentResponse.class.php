<?php

class AltapayGetPaymentResponse extends AltapayAbstractPaymentResponse
{
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
