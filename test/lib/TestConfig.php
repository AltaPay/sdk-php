<?php

class TestConfig
{
    /** @var string */
    public $installation = 'https://testgateway.altapay.com';
    /** @var string */
    public $username = 'username';
    /** @var string */
    public $password = 'secret';
    /** @var string */
    public $terminal = 'AltaPay Test Terminal';
    /** @var string */
    public $currency = 'EUR';

    public function __construct()
    {
        if (defined('ALTAPAY_INTEGRATION_INSTALLATION')) {
            $this->installation = ALTAPAY_INTEGRATION_INSTALLATION;
        }
        if (defined('ALTAPAY_INTEGRATION_USERNAME')) {
            $this->username = ALTAPAY_INTEGRATION_USERNAME;
        }
        if (defined('ALTAPAY_INTEGRATION_PASSWORD')) {
            $this->password = ALTAPAY_INTEGRATION_PASSWORD;
        }
        if (defined('ALTAPAY_INTEGRATION_TERMINAL')) {
            $this->terminal = ALTAPAY_INTEGRATION_TERMINAL;
        }
        if (defined('ALTAPAY_INTEGRATION_CURRENCY')) {
            $this->currency = ALTAPAY_INTEGRATION_CURRENCY;
        }
    }
}
