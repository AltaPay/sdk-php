<?php

require_once(dirname(__FILE__).'/PensioAbstractResponse.class.php');
require_once(dirname(__FILE__).'/PensioTerminal.class.php');

class PensioGetTerminalsResponse extends PensioAbstractResponse
{
	private $terminals = array();
	
	public function __construct(SimpleXmlElement $xml)
	{
		parent::__construct($xml);
		
		if($this->getErrorCode() === '0')
		{
			foreach($xml->Body->Terminals->Terminal as $terminalXml)
			{
				$terminal = new PensioTerminal();
				$terminal->setTitle((string)$terminalXml->Title);
				$terminal->setCountry((string)$terminalXml->Country);
				foreach($terminalXml->Natures->Nature as $nature)
				{
					$terminal->addNature((string)$nature);
				}
				foreach($terminalXml->Currencies->Currency as $currency)
				{
					$terminal->addCurrency((string)$currency);
				}
				
				$this->terminals[] = $terminal;
			}
		}
	}
	
	public function getTerminals()
	{
		return $this->terminals;
	}
	
	public function wasSuccessful()
	{
		return $this->getErrorCode() === '0';
	}
}