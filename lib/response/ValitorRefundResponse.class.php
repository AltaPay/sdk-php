<?php

class ValitorRefundResponse extends ValitorAbstractPaymentResponse
{
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXmlElement $body
     *
     * @return mixed|void
     */
    protected function parseBody(SimpleXmlElement $body)
    {
    }
}
