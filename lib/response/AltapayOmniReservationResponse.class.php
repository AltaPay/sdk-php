<?php

class AltapayOmniReservationResponse extends AltapayAbstractPaymentResponse
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
        if (parent::wasSuccessful()) {
            // There must be at least one Payment
            if ($this->getPrimaryPayment() !== null) {
                // If the current state is supposed to be more than 'created'
                return $this->getPrimaryPayment()->getCurrentStatus() != 'created';
            }
        }
        return false;
    }
}
