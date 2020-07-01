<?php

class ValitorCaptureRecurringResponse extends ValitorPreauthRecurringResponse
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
