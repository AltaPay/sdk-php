<?php

class ValitorUpdateOrderResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorUpdateOrderResponse constructor.
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXmlElement $body
     * @return mixed|void
     */
    protected function parseBody(SimpleXmlElement $body)
    {

    }
}