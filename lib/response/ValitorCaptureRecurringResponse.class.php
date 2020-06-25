<?php

class ValitorCaptureRecurringResponse extends ValitorPreauthRecurringResponse
{
    /**
     * @return bool
     */
    public function wasSubscriptionReleased()
    {
        return $this->getSubscriptionPayment()->isReleased();
    }
}
