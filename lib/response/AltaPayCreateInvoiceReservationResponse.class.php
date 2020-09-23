<?php

class AltaPayCreateInvoiceReservationResponse extends AltaPayAbstractResponse
{
    /** @var string */
    private $result;

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
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
