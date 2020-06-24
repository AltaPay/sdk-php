<?php

/**
 * [ReconciliationIdentifier] => SimpleXMLElement Object
 * (
 * [Id] => 5a9d09b7-4784-4d47-aebc-c0ac63b56722
 * [Amount] => 105
 * [Type] => captured
 * [Date] => 2011-08-31T23:36:14+02:00
 * ).
 *
 * @author emanuel
 */
class ValitorAPIReconciliationIdentifier
{
    private $id;
    private $amount;
    private $type;
    private $date;

    /**
     * ValitorAPIReconciliationIdentifier constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->id = (string)$xml->Id;
        $this->amount = (string)$xml->Amount;
        $this->type = (string)$xml->Type;
        $this->date = (string)$xml->Date;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
