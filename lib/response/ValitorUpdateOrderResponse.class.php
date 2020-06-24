<?php

class ValitorUpdateOrderResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorUpdateOrderResponse constructor.
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
     * @return mixed|void
     */
    protected function parseBody(SimpleXMLElement $body)
    {
    }
}
