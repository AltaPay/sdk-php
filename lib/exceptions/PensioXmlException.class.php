<?php

class PensioXmlException extends Exception
{
	/**
	 * @var SimpleXMLElement
	 */
	private $xml;

	public function __construct($message, SimpleXMLElement $xml)
	{
		parent::__construct($message ."\n\n".$xml);
		$this->xml = $xml;
	}

	public function getXml()
	{
		return $this->xml;
	}
}
