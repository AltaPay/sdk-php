<?php

interface IValitorHttpUtils
{
    /**
     * @param ValitorHttpRequest $request
     *
     * @return ValitorHttpResponse
     */
    public function requestURL(ValitorHttpRequest $request);
}
