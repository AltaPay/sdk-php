<?php

class AltapayRefundResponse extends AltapayAbstractPaymentResponse
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
