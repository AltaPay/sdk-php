<?php

class AltapayGetTerminalsResponse extends AltapayAbstractResponse
{
    /** @var AltapayTerminal[] */
    private $terminals = array();

    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);

        if ($this->getErrorCode() === '0') {
            foreach ($xml->Body->Terminals->Terminal as $terminalXml) {
                $terminal = new AltapayTerminal();
                $terminal->setTitle((string)$terminalXml->Title);
                $terminal->setCountry((string)$terminalXml->Country);
                foreach ($terminalXml->Natures->Nature as $nature) {
                    $terminal->addNature((string)$nature);
                }
                foreach ($terminalXml->Currencies->Currency as $currency) {
                    $terminal->addCurrency((string)$currency);
                }

                $this->terminals[] = $terminal;
            }
        }
    }

    /**
     * @return AltapayTerminal[]
     */
    public function getTerminals()
    {
        return $this->terminals;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->getErrorCode() === '0';
    }
}
