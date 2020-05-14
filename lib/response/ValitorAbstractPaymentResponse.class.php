<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractResponse.class.php';
require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAPIPayment.class.php';

/**
 * Class ValitorAbstractPaymentResponse
 */
abstract class ValitorAbstractPaymentResponse extends ValitorAbstractResponse
{
    private $result;
    private $merchantErrorMessage, $cardHolderErrorMessage, $cardHolderMessageMustBeShown;
    protected $payments = array();

    /**
     * ValitorAbstractPaymentResponse constructor.
     * @param SimpleXmlElement $xml
     * @throws Exception
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
        $this->initFromXml($xml);
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->initFromXml(new SimpleXmlElement($this->xml));
    }

    /**
     * @param SimpleXmlElement $xml
     * @throws Exception
     */
    private function initFromXml(SimpleXmlElement $xml)
    {
        $this->payments = array();
        if($this->getErrorCode() === '0') {
            $this->result = strval($xml->Body->Result);
            $this->merchantErrorMessage = (string)$xml->Body->MerchantErrorMessage;
            $this->cardHolderErrorMessage = (string)$xml->Body->CardHolderErrorMessage;
            $this->cardHolderMessageMustBeShown = (string)$xml->Body->CardHolderMessageMustBeShown;
            
            $this->parseBody($xml->Body);

            if(isset($xml->Body->Transactions->Transaction)) {
                foreach($xml->Body->Transactions->Transaction as $transactionXml)
                {
                    $this->addPayment(new ValitorAPIPayment($transactionXml));
                }
            }
        }
    }

    /**
     * @param ValitorAPIPayment $payment
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
     * @param SimpleXmlElement $body
     * @return mixed
     */
    abstract protected function parseBody(SimpleXmlElement $body);
}