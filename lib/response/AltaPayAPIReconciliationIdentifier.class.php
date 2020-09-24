<?php

/**
 * This class represents the following data structure.
 *
 * <ReconciliationIdentifier>
 *     <Id>5a9d09b7-4784-4d47-aebc-c0ac63b56722</Id>
 *     <Amount>105</Amount>
 *     <Type>captured</Type>
 *     <Date>2011-08-31T23:36:14+02:00</Date>
 * </ReconciliationIdentifier>
 */
class AltaPayAPIReconciliationIdentifier
{
    /** @var string */
    private $id;
    /** @var string */
    private $amount;
    /** @var string */
    private $type;
    /** @var string */
    private $date;

    /**
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
