<?php

/**
 * Class ValitorAPITransactionsRequest.
 */
class ValitorAPITransactionsRequest
{
    /** @var string */
    private $shop;
    /** @var string */
    private $terminal;
    /** @var string */
    private $transaction;
    /** @var string */
    private $transactionId;
    /** @var string */
    private $shopOrderId;
    /** @var string */
    private $paymentStatus;
    /** @var string */
    private $reconciliationIdentifier;
    /** @var string */
    private $acquirerReconciliationIdentifier;

    /**
     * @return string
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param string $shop
     *
     * @return void
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return string
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param string $terminal
     *
     * @return void
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;
    }

    /**
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param string $transaction
     *
     * @return void
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return void
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getShopOrderId()
    {
        return $this->shopOrderId;
    }

    /**
     * @param string $shopOrderId
     *
     * @return void
     */
    public function setShopOrderId($shopOrderId)
    {
        $this->shopOrderId = $shopOrderId;
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     *
     * @return void
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return string
     */
    public function getReconciliationIdentifier()
    {
        return $this->reconciliationIdentifier;
    }

    /**
     * @param string $reconciliationIdentifier
     *
     * @return void
     */
    public function setReconciliationIdentifier($reconciliationIdentifier)
    {
        $this->reconciliationIdentifier = $reconciliationIdentifier;
    }

    /**
     * @return string
     */
    public function getAcquirerReconciliationIdentifier()
    {
        return $this->acquirerReconciliationIdentifier;
    }

    /**
     * @param string $acquirerReconciliationIdentifier
     *
     * @return void
     */
    public function setAcquirerReconciliationIdentifier($acquirerReconciliationIdentifier)
    {
        $this->acquirerReconciliationIdentifier = $acquirerReconciliationIdentifier;
    }

    /**
     * @return string[]
     */
    public function asArray()
    {
        $array = array();
        if ($this->shop !== null) {
            $array['shop'] = $this->shop;
        }
        if ($this->terminal !== null) {
            $array['terminal'] = $this->terminal;
        }
        if ($this->transaction !== null) {
            $array['transaction'] = $this->transaction;
        }
        if ($this->transactionId !== null) {
            $array['transaction_id'] = $this->transactionId;
        }
        if ($this->shopOrderId !== null) {
            $array['shop_orderid'] = $this->shopOrderId;
        }
        if ($this->paymentStatus !== null) {
            $array['payment_status'] = $this->paymentStatus;
        }
        if ($this->reconciliationIdentifier !== null) {
            $array['reconciliation_identifier'] = $this->reconciliationIdentifier;
        }
        if ($this->acquirerReconciliationIdentifier !== null) {
            $array['acquirer_reconciliation_identifier'] = $this->acquirerReconciliationIdentifier;
        }

        return $array;
    }
}
