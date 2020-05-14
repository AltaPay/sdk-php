<?php

if(!defined('VALITOR_API_ROOT')) {
    define('VALITOR_API_ROOT', dirname(__DIR__));
}

require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'http'. DIRECTORY_SEPARATOR .'ValitorHttpRequest.class.php';
require_once VALITOR_API_ROOT. DIRECTORY_SEPARATOR .'http'. DIRECTORY_SEPARATOR .'ValitorHttpResponse.class.php';

interface IValitorHttpUtils
{
    /**
     * @param ValitorHttpRequest $request
     * @return ValitorHttpResponse
     */
    public function requestURL(ValitorHttpRequest $request);
}