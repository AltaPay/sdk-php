<?php

class AltapayConnectionFailedException extends AltapayMerchantAPIException
{
    /**
     * @param string $url
     * @param string $reason
     */
    public function __construct($url, $reason)
    {
        parent::__construct('Connection to '.$url.' failed (reason: '.$reason.')', 23483431);
    }
}
