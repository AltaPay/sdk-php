<?php

interface IAltapayHttpUtils
{
    /**
     * @param AltapayHttpRequest $request
     *
     * @return AltapayHttpResponse
     */
    public function requestURL(AltapayHttpRequest $request);
}
