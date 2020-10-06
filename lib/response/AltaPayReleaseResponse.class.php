<?php

class AltapayReleaseResponse extends AltapayAbstractPaymentResponse
{
    /**
     * @param SimpleXMLElement $body
     *
     * @return mixed|void
     */
    protected function parseBody(SimpleXMLElement $body)
    {
    }
}
