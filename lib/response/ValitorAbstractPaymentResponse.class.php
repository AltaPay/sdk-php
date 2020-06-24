<?php

/**
 * Class ValitorAbstractPaymentResponse.
 */
abstract class ValitorAbstractPaymentResponse extends ValitorAbstractResponse
{
    private $result;
    private $merchantErrorMessage;
    private $cardHolderErrorMessage;
    private $cardHolderMessageMustBeShown;
    protected $payments = array();

    /**
     * ValitorAbstractPaymentResponse constructor.
     *
     * @param SimpleXMLElement $xml
     *
     * @throws Exception
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        $this->initFromXml($xml);
    }

    /**
     * @return void
     */
    public function __wakeup()
    {
        $this->initFromXml(new SimpleXMLElement($this->xml));
    }

    /**
     * @param SimpleXMLElement $xml
     *
     * @throws Exception
     *
     * @return void
     */
    private function initFromXml(SimpleXMLElement $xml)
    {
        $this->payments = array();
        if ($this->getErrorCode() === '0') {
            $this->result = (string)($xml->Body->Result);
            $this->merchantErrorMessage = (string)$xml->Body->MerchantErrorMessage;
            $this->cardHolderErrorMessage = (string)$xml->Body->CardHolderErrorMessage;
            $this->cardHolderMessageMustBeShown = (string)$xml->Body->CardHolderMessageMustBeShown;

            $this->parseBody($xml->Body);

            if (isset($xml->Body->Transactions->Transaction)) {
                foreach ($xml->Body->Transactions->Transaction as $transactionXml) {
                    $this->addPayment(new ValitorAPIPayment($transactionXml));
                }
            }
        }
    }

    /**
     * @param ValitorAPIPayment $payment
     *
     * @return void
     */
    private function addPayment(ValitorAPIPayment $payment)
    {
        $this->payments[] = $payment;
    }

    /**
     * @return ValitorAPIPayment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @return ValitorAPIPayment
     */
    public function getPrimaryPayment()
    {
        return isset($this->payments[0]) ? $this->payments[0] : null;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && $this->result == 'Success';
    }

    /**
     * @return bool
     */
    public function wasDeclined()
    {
        return $this->getErrorCode() === '0' && $this->result == 'Failed';
    }

    /**
     * @return bool
     */
    public function wasErroneous()
    {
        return $this->getErrorCode() !== '0' || $this->result == 'Error';
    }

    /**
     * @return mixed
     */
    public function getMerchantErrorMessage()
    {
        return $this->merchantErrorMessage;
    }

    /**
     * @return mixed
     */
    public function getCardHolderErrorMessage()
    {
        return $this->cardHolderErrorMessage;
    }

    /**
     * @return mixed
     */
    public function getCardHolderMessageMustBeShown()
    {
        return $this->cardHolderMessageMustBeShown;
    }

    /**
     * @param SimpleXMLElement $body
     *
     * @return mixed
     */
    abstract protected function parseBody(SimpleXMLElement $body);
}
