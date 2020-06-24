<?php

class ValitorReservationResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorReservationResponse constructor.
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
