<?php

class ValitorAPIAddress
{
    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var string */
    private $address;
    /** @var string */
    private $city;
    /** @var string */
    private $region;
    /** @var string */
    private $country;
    /** @var string */
    private $postalCode;

    /**
     * ValitorAPIAddress constructor.
     *
     * @param SimpleXMLElement $xml
     *                              <BillingAddress>
     *                              <Firstname><![CDATA[Kødpålæg >-) <script>alert(42);</script>]]></Firstname>
     *                              <Lastname><![CDATA[Lyn]]></Lastname>
     *                              <Address><![CDATA[Rosenkæret 13]]></Address>
     *                              <City><![CDATA[Søborg]]></City>
     *                              <Region><![CDATA[]]></Region>
     *                              <Country><![CDATA[DK]]></Country>
     *                              <PostalCode><![CDATA[2860]]></PostalCode>
     *                              </BillingAddress>
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $attrs = $xml->attributes();

        $this->firstName = (string)$xml->Firstname;
        $this->lastName = (string)$xml->Lastname;
        $this->address = (string)$xml->Address;
        $this->city = (string)$xml->City;
        $this->region = (string)$xml->Region;
        $this->country = (string)$xml->Country;
        $this->postalCode = (string)$xml->PostalCode;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
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
    public function getPostalCode()
    {
        return $this->postalCode;
    }
}
