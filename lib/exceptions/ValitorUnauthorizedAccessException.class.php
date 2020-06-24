<?php

class ValitorUnauthorizedAccessException extends ValitorMerchantAPIException
{
    /**
     * ValitorUnauthorizedAccessException constructor.
     *
     * @param $url
     * @param $username
     */
    public function __construct($url, $username)
    {
        parent::__construct('Unauthorized access to '.$url.' for user '.$username, 9283745);
    }
}
