<?php
require_once(dirname(__FILE__).'/PensioHttpRequest.class.php');
require_once(dirname(__FILE__).'/PensioHttpResponse.class.php');

interface IPensioHttpUtils
{
	/**
	 * @return PensioHttpResponse
	 */
	public function requestURL(PensioHttpRequest $request);
}