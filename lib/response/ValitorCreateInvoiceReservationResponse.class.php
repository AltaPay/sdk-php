<?php

/**
 * Class ValitorCreateInvoiceReservationResponse.
 */
class ValitorCreateInvoiceReservationResponse extends ValitorAbstractResponse
{
    /** @var string */
    private $result;

    /**
     * ValitorCreateInvoiceReservationResponse constructor.
     *
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
