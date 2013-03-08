<?php

class RequestTimeoutException extends PensioMerchantAPIException
{
	public function __construct($url)
	{
		parent::__construct("Request to ".$url." timed out", 39824714);
	}
}