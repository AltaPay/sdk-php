<?php

class AltapayFundingListResponse extends AltapayAbstractResponse
{
    /** @var int */
    private $numberOfPages;
    /** @var AltapayAPIFunding[] */
    private $fundings = array();

    /**
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        if ($this->getErrorCode() === '0') {
            $attr = $xml->Body->Fundings->attributes();
            $this->numberOfPages = isset($attr['numberOfPages']) ? (int)$attr['numberOfPages'] : 0;
            foreach ($xml->Body->Fundings->Funding as $funding) {
                $this->fundings[] = new AltapayAPIFunding($funding);
            }
        }
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getNumberOfPages() > 0;
    }

    /**
     * @return int
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    /**
     * @return AltapayAPIFunding[]
     */
    public function getFundings()
    {
        return $this->fundings;
    }
}
