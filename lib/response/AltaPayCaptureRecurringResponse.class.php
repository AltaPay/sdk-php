<?php

class AltapayCaptureRecurringResponse extends AltapayPreauthRecurringResponse
{
    /**
     * @return ?bool
     */
    public function wasSubscriptionReleased()
    {
        $payment = $this->getSubscriptionPayment();
        return $payment ? $payment->isReleased() : null;
    }
}
