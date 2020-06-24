<?php

class ValitorCaptureResponse extends ValitorAbstractPaymentResponse
{
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    protected function parseBody(SimpleXmlElement $body)
    {
    }
}
