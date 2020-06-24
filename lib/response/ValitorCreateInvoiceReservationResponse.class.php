<?php

/**
 * Class ValitorCreateInvoiceReservationResponse.
 */
class ValitorCreateInvoiceReservationResponse extends ValitorAbstractResponse
{
    private $result;

    /**
     * ValitorCreateInvoiceReservationResponse constructor.
     *
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);

        if ($this->getErrorCode() === '0') {
            $this->result = (string)$xml->Body->Result;
        }
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && $this->result == 'Success';
    }
}
