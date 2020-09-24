<?php

interface IAltaPayHttpUtils
{
    /**
     * @param AltaPayHttpRequest $request
     *
     * @return AltaPayHttpResponse
     */
    public function requestURL(AltaPayHttpRequest $request);
}
