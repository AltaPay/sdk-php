<?php

class AltaPayUnauthorizedAccessException extends AltaPayMerchantAPIException
{
    /**
     * @param string $url
     * @param string $username
     */
    public function __construct($url, $username)
    {
        parent::__construct('Unauthorized access to '.$url.' for user '.$username, 9283745);
    }
}
