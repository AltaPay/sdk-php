<?php

class AltaPayPreauthRecurringResponse extends AltaPayAbstractPaymentResponse
{
    /**
     * @param SimpleXMLElement $body
     *
     * @return mixed|void
     */
    protected function parseBody(SimpleXMLElement $body)
    {
    }

    /**
     * This payment represent the subscription, it is returned as the subscription it
     * self might have changed since last time it was used.
     *
     * @return AltaPayAPIPayment|null
     */
    public function getSubscriptionPayment()
    {
        return isset($this->payments[0]) ? $this->payments[0] : null;
    }

    /**
     * This is the payment which was pre-authed.
     *
     * @return AltaPayAPIPayment|null
     */
    public function getPrimaryPayment()
    {
        return isset($this->payments[1]) ? $this->payments[1] : null;
    }
}
