<?php

class ValitorAPIPaymentNatureService
{
    /** @var string|null */
    private $name;
    /** @var string */
    private $supportsRefunds;
    /** @var string */
    private $supportsRelease;
    /** @var string */
    private $supportsMultipleCaptures;
    /** @var string */
    private $supportsMultipleRefunds;
    /** @var SimpleXMLElement */
    private $simpleXmlElement;

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->simpleXmlElement = $xml;

        $attrs = $xml->attributes();

        $this->name = isset($attrs['name']) ? (string)$attrs['name'] : null;
        $this->supportsRefunds = (string)$xml->SupportsRefunds;
        $this->supportsRelease = (string)$xml->SupportsRelease;
        $this->supportsMultipleCaptures = (string)$xml->SupportsMultipleCaptures;
        $this->supportsMultipleRefunds = (string)$xml->SupportsMultipleRefunds;
    }

    /**
     * @return string|null
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
