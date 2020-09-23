<?php

class AltaPayRequestTimeoutException extends AltaPayMerchantAPIException
{
    /**
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct('Request to '.$url.' timed out', 39824714);
    }
}
