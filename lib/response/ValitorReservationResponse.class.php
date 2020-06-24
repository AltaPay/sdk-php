<?php

class ValitorReservationResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorReservationResponse constructor.
     *
     * @param SimpleXmlElement $xml
     */
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
