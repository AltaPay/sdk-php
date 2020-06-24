<?php

/**
 * Class ValitorAPITransactionsRequest.
 */
class ValitorAPITransactionsRequest
{
    private $shop;
    private $terminal;
    private $transaction;
    private $transactionId;
    private $shopOrderId;
    private $paymentStatus;
    private $reconciliationIdentifier;
    private $acquirerReconciliationIdentifier;

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param $shop
     *
     * @return void
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return mixed
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param $terminal
     *
     * @return void
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param $transaction
     *
     * @return void
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param $transactionId
     *
     * @return void
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return mixed
     */
    public function getShopOrderId()
    {
        return $this->shopOrderId;
    }

    /**
     * @param $shopOrderId
     *
     * @return void
     */
    public function setShopOrderId($shopOrderId)
    {
        $this->shopOrderId = $shopOrderId;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param $paymentStatus
     *
     * @return void
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return mixed
     */
    public function getReconciliationIdentifier()
    {
        return $this->reconciliationIdentifier;
    }

    /**
     * @param $reconciliationIdentifier
     *
     * @return void
     */
    public function setReconciliationIdentifier($reconciliationIdentifier)
    {
        $this->reconciliationIdentifier = $reconciliationIdentifier;
    }

    /**
     * @return mixed
     */
    public function getAcquirerReconciliationIdentifier()
    {
        return $this->acquirerReconciliationIdentifier;
    }

    /**
     * @param $acquirerReconciliationIdentifier
     *
     * @return void
     */
    public function setAcquirerReconciliationIdentifier($acquirerReconciliationIdentifier)
    {
        $this->acquirerReconciliationIdentifier = $acquirerReconciliationIdentifier;
    }

    /**
     * @return array
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
