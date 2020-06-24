<?php

class ValitorConnectionFailedException extends ValitorMerchantAPIException
{
    /**
     * ValitorConnectionFailedException constructor.
     *
     * @param $url
     * @param $reason
     */
    public function __construct($url, $reason)
    {
        parent::__construct('Connection to '.$url.' failed (reason: '.$reason.')', 23483431);
    }
}
