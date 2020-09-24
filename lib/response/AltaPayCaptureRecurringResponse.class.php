<?php

class AltaPayCaptureRecurringResponse extends AltaPayPreauthRecurringResponse
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
