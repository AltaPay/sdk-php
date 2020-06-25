<?php

/**
 * Class ValitorTerminal.
 */
class ValitorTerminal
{
    /** @var string */
    private $title;
    /** @var string */
    private $country;
    /** @var string[] */
    private $natures = array();
    /** @var string[] */
    private $currencies = array();

    /**
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $country
     *
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @param string $nature
     *
     * @return void
     */
    public function addNature($nature)
    {
        $this->natures[] = $nature;
    }

    /**
     * @return string[]
     */
    public function getNature()
    {
        return $this->natures;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    public function addCurrency($currency)
    {
        $this->currencies[] = $currency;
    }

    /**
     * @param string $currency
     *
     * @return bool
     */
    public function hasCurrency($currency)
    {
        if (!empty($this->currencies)) {
            return in_array('XXX', $this->currencies) || in_array($currency, $this->currencies);
        }
        return true;
    }
}
