<?php

/**
 * SimpleXMLElement Object
 * (
 * [Filename] => fundingDownloadTest
 * [ContractIdentifier] => FunctionalTestContractID
 * [Shops] => SimpleXMLElement Object
 * (
 * [Shop] => Valitor Functional Test Shop
 * ).
 *
 * [Acquirer] => TestAcquirer
 * [FundingDate] => 2010-12-24
 * [Amount] => 0.00 EUR
 * [CreatedDate] => 2013-01-19
 * [DownloadLink] => http://gateway.dev.valitor.com/merchant.php/API/fundingDownload?id=1
 * )
 *
 * @author emanuel
 */
class ValitorAPIFunding
{
    /** @var string */
    private $filename;
    /** @var string */
    private $contractIdentifier;
    /** @var string[] */
    private $shops = array();
    /** @var string */
    private $acquirer;
    /** @var string */
    private $fundingDate;
    /** @var string */
    private $amount;
    /** @var string */
    private $currency;
    /** @var string */
    private $createdDate;
    /** @var string */
    private $downloadLink;
    /** @var string */
    private $referenceText;

    /**
     * ValitorAPIFunding constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->filename = (string)$xml->Filename;
        $this->contractIdentifier = (string)$xml->ContractIdentifier;
        foreach ($xml->Shops->Shop as $shop) {
            $this->shops[] = (string)$shop;
        }
        $this->acquirer = (string)$xml->Acquirer;
        $this->fundingDate = (string)$xml->FundingDate;
        list($this->amount, $this->currency) = explode(' ', (string)$xml->Amount, 2);
        $this->createdDate = (string)$xml->CreatedDate;
        $this->downloadLink = (string)$xml->DownloadLink;
        $this->referenceText = (string)$xml->ReferenceText;
    }

    /**
     * @return string
     */
    public function getFundingDate()
    {
        return $this->fundingDate;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getContractIdentifier()
    {
        return $this->contractIdentifier;
    }

    /**
     * @return string[]
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * @return string
     */
    public function getAcquirer()
    {
        return $this->acquirer;
    }

    /**
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return string
     */
    public function getReferenceText()
    {
        return $this->referenceText;
    }
}
