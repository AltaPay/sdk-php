<?php

class ValitorUnauthorizedAccessException extends ValitorMerchantAPIException
{
    /**
     * ValitorUnauthorizedAccessException constructor.
     *
     * @param string $url
     * @param string $username
     */
    public function __construct($url, $username)
    {
        parent::__construct('Unauthorized access to '.$url.' for user '.$username, 9283745);
    }
}
