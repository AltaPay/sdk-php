<?php

class TestConfig
{
    /** @var string */
    public $installation = 'https://testgateway.valitor.com';
    /** @var string */
    public $username = 'username';
    /** @var string */
    public $password = 'secret';
    /** @var string */
    public $terminal = 'Valitor Test Terminal';
    /** @var string */
    public $currency = 'EUR';

    public function __construct()
    {
        if (defined('VALITOR_INTEGRATION_INSTALLATION')) {
            $this->installation = VALITOR_INTEGRATION_INSTALLATION;
        }
        if (defined('VALITOR_INTEGRATION_USERNAME')) {
            $this->username = VALITOR_INTEGRATION_USERNAME;
        }
        if (defined('VALITOR_INTEGRATION_PASSWORD')) {
            $this->password = VALITOR_INTEGRATION_PASSWORD;
        }
        if (defined('VALITOR_INTEGRATION_TERMINAL')) {
            $this->terminal = VALITOR_INTEGRATION_TERMINAL;
        }
        if (defined('VALITOR_INTEGRATION_CURRENCY')) {
            $this->currency = VALITOR_INTEGRATION_CURRENCY;
        }
    }
}
