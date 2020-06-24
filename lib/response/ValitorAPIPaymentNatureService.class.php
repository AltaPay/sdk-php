<?php

/**
 * Class ValitorAPIPaymentNatureService.
 */
class ValitorAPIPaymentNatureService
{
    private $name;
    private $supportsRefunds;
    private $supportsRelease;
    private $supportsMultipleCaptures;
    private $supportsMultipleRefunds;
    private $simpleXmlElement;

    /**
     * ValitorAPIPaymentNatureService constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->simpleXmlElement = $xml;

        $attrs = $xml->attributes();

        $this->name = (string)(@$attrs['name']);
        $this->supportsRefunds = (string)$xml->SupportsRefunds;
        $this->supportsRelease = (string)$xml->SupportsRelease;
        $this->supportsMultipleCaptures = (string)$xml->SupportsMultipleCaptures;
        $this->supportsMultipleRefunds = (string)$xml->SupportsMultipleRefunds;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSupportsRefunds()
    {
        return $this->supportsRefunds;
    }

    /**
     * @return string
     */
    public function getSupportsRelease()
    {
        return $this->supportsRelease;
    }

    /**
     * @return string
     */
    public function getSupportsMultipleCaptures()
    {
        return $this->supportsMultipleCaptures;
    }

    /**
     * @return string
     */
    public function getSupportsMultipleRefunds()
    {
        return $this->supportsMultipleRefunds;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getXmlElement()
    {
        return $this->simpleXmlElement;
    }
}
