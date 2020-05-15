<?php
if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'response'. DIRECTORY_SEPARATOR .'ValitorAbstractPaymentResponse.class.php';

/**
 * Class ValitorOmniReservationResponse
 */
class ValitorOmniReservationResponse extends ValitorAbstractPaymentResponse
{
    /**
     * ValitorOmniReservationResponse constructor.
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @param SimpleXmlElement $body
     */
    protected function parseBody(SimpleXmlElement $body)
    {
        
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        if(parent::wasSuccessful()) {
            // There must be at least one Payment
            if(!is_null($this->getPrimaryPayment())) {
                // If the current state is supposed to be more than 'created'
                return $this->getPrimaryPayment()->getCurrentStatus() != 'created';
            }
        }
        return false;
    }

}