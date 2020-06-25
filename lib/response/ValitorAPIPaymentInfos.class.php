<?php

/**
 * This class represents the following data structure.
 *
 * <PaymentInfos>
 *      <PaymentInfo name="auxkey">aux data (&lt;&#xE6;&#xF8;&#xE5;&gt;)</PaymentInfo>
 * </PaymentInfos>
 */
class ValitorAPIPaymentInfos
{
    /** @var SimpleXMLElement
     */
    private $simpleXmlElement;
    /** @var array<string, string> */
    private $infos = array();

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->simpleXmlElement = $xml;
        if (isset($xml->PaymentInfo)) {
            foreach ($xml->PaymentInfo as $paymentInfo) {
                $attrs = $paymentInfo->attributes();
                $this->infos[(string)$attrs['name']] = (string)$paymentInfo;
            }
        }
    }

    /**
     * @return array<string, string>
     */
    public function getAll()
    {
        return $this->infos;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getInfo($key)
    {
        return isset($this->infos[$key]) ? $this->infos[$key] : null;
    }

    /**
     * @return SimpleXMLElement an XML representation of the object as it was instantiated
     */
    public function getXmlElement()
    {
        return $this->simpleXmlElement;
    }
}
