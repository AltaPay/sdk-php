<?php

/**
 * Class ValitorAPIChargebackEvent.
 */
class ValitorAPIChargebackEvent
{
    /** @var DateTime */
    private $date;
    /** @var string */
    private $type;
    /** @var string */
    private $reasonCode;
    /** @var string */
    private $reason;
    /** @var string */
    private $amount;
    /** @var string */
    private $currency;
    /** @var array<string, string> */
    private $additionalInfo = array();

    /**
     * ValitorAPIChargebackEvent constructor.
     *
     * @param SimpleXMLElement $xml
     *
     * @throws Exception
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->date = new DateTime((string)$xml->Date);
        $this->type = (string)$xml->Type;
        $this->reasonCode = (string)$xml->ReasonCode;
        $this->reason = (string)$xml->Reason;
        $this->amount = (string)$xml->Amount;
        $this->currency = (string)$xml->Currency;

        $additionalInfoXml = @simplexml_load_string((string)$xml->AdditionalInfo);
        if ($additionalInfoXml) {
            foreach ($additionalInfoXml->info_element as $infoElement) {
                $this->additionalInfo[(string)$infoElement->key] = (string)$infoElement->value;
            }
        }
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     *
     * @return DateTime
     */
    public function setDate($date)
    {
        return $this->date = $date;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function setType($type)
    {
        return $this->type = $type;
    }

    /**
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param string $reasonCode
     *
     * @return string
     */
    public function setReasonCode($reasonCode)
    {
        return $this->reasonCode = $reasonCode;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     *
     * @return string
     */
    public function setReason($reason)
    {
        return $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     *
     * @return string
     */
    public function setAmount($amount)
    {
        return $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return string
     */
    public function setCurrency($currency)
    {
        return $this->currency = $currency;
    }

    /**
     * @return array<string, string>
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param array<string, string> $additionalInfo
     *
     * @return array<string, string>
     */
    public function setAdditionalInfo(array $additionalInfo)
    {
        return $this->additionalInfo = $additionalInfo;
    }
}
