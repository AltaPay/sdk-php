<?php

/**
 * Class ValitorTerminal.
 */
class ValitorTerminal
{
    private $title;
    private $country;
    private $natures = array();
    private $currencies = array();

    /**
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return void
     */
    public function addNature($nature)
    {
        $this->natures[] = $nature;
    }

    /**
     * @return array
     */
    public function getNature()
    {
        return $this->natures;
    }

    /**
     * @return void
     */
    public function addCurrency($currency)
    {
        $this->currencies[] = $currency;
    }

    /**
     * @return bool
     */
    public function hasCurrency($currency)
    {
        if (!empty($this->currencies)) {
            return in_array('XXX', $this->currencies) || in_array($currency, $this->currencies);
        } else {
            return true;
        }
    }
}
