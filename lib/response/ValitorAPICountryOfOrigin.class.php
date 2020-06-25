<?php

/**
 * Class ValitorAPICountryOfOrigin.
 */
class ValitorAPICountryOfOrigin
{
    const NotSet = 'NotSet';
    const CardNumber = 'CardNumber';
    const BankAccount = 'BankAccount';
    const BillingAddress = 'BillingAddress';
    const RegisteredAddress = 'RegisteredAddress';
    const ShippingAddress = 'ShippingAddress';
    const PayPal = 'PayPal';

    /** @var string */
    private $country;
    /** @var string */
    private $source;

    /**
     * ValitorAPICountryOfOrigin constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->country = (string)$xml->Country;
        $this->source = (string)$xml->Source;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
