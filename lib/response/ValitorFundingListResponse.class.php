<?php

/**
 * Class ValitorFundingListResponse.
 */
class ValitorFundingListResponse extends ValitorAbstractResponse
{
    private $numberOfPages;
    private $fundings = array();

    /**
     * ValitorFundingListResponse constructor.
     *
     * @param SimpleXmlElement $xml
     */
    public function __construct(SimpleXmlElement $xml)
    {
        parent::__construct($xml);
        if ($this->getErrorCode() === '0') {
            $attr = $xml->Body->Fundings->attributes();
            $this->numberOfPages = (string)$attr['numberOfPages'];
            foreach ($xml->Body->Fundings->Funding as $funding) {
                $this->fundings[] = new ValitorAPIFunding($funding);
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
     * @return string
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    /**
     * @return array
     */
    public function getFundings()
    {
        return $this->fundings;
    }
}
