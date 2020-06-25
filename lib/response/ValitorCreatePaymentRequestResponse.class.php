<?php

class ValitorCreatePaymentRequestResponse extends ValitorAbstractResponse
{
    /** @var string */
    private $redirectURL;
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
            $this->redirectURL = (string)$xml->Body->Url;
        }
    }

    /**
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0' && $this->result == 'Success';
    }
}
