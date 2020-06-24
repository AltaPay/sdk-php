<?php

/**
 * Class ValitorCreatePaymentRequestResponse.
 */
class ValitorCreatePaymentRequestResponse extends ValitorAbstractResponse
{
    private $redirectURL;
    private $result;

    /**
     * ValitorCreatePaymentRequestResponse constructor.
     *
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
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
