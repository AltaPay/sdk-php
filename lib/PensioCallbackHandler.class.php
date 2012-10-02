<?php

require_once(dirname(__FILE__).'/PensioMerchantAPI.class.php');

/**
 * The purpose of this class is to parse the callback parameters and return
 * a usefull response object from which your business logic can get information
 * for the decisions it needs to make.
 * 
 * @author "Emanuel Holm Greisen" <phpclientapi@pensio.com>
 */
class PensioCallbackHandler
{
	/**
	 * @return PensioAbstractPaymentResponse
	 */
	public function parseXmlResponse($xml)
	{
		if(!($xml instanceof SimpleXMLElement))
		{
			$xml = new SimpleXMLElement($xml);
		}
		$this->verifyXml($xml);
		
		// This is not a perfect way of figuring out what kind of response would be appropriate
		// At some point we should have a more direct link between something in the header
		// and the way the result should be interpreted. 
		$authType = $xml->Body[0]->Transactions[0]->Transaction[0]->AuthType;
		switch($authType)
		{
			case 'payment':
			case 'paymentAndCapture':
			case 'recurring':
			case 'subscription':
			case 'verifyCard':
				return new PensioReservationResponse($xml);
			case 'subscriptionAndCharge':
			case 'recurringAndCapture':
				return new PensioCaptureRecurringResponse($xml);
			default:
				throw new Exception("Unsupported 'authType': (".$authType.")");				
		}
	}
	
	private function verifyXml(SimpleXMLElement $xml)
	{
		if($xml->getName() != 'APIResponse')
		{
			throw new Exception("Unknown root-tag <".$xml->getName()."> in XML, should have been <APIResponse>");
		}
		if(!isset($xml->Header))
		{
			throw new Exception("No <Header> in response");
		}
		if(!isset($xml->Header->ErrorCode))
		{
			throw new Exception("No <ErrorCode> in Header of response");
		}
		if((string)$xml->Header->ErrorCode !== '0')
		{
			throw new Exception($xml->Header->ErrorMessage.' (Error code: '.$xml->Header->ErrorCode.')');
		}
		if(!isset($xml->Body))
		{
			throw new Exception("No <Body> in response");
		}
		if(!isset($xml->Body[0]->Transactions))
		{
			$error = $this->getBodyMerchantErrorMessage($xml);
			throw new Exception("No <Transactions> in <Body> of response".($error ? ' ('.$error.')' : ''));
		}
		if(!isset($xml->Body[0]->Transactions[0]->Transaction))
		{
			$error = $this->getBodyMerchantErrorMessage($xml);
			throw new Exception("No <Transaction> in <Transactions> of response".($error ? ' ('.$error.')' : ''));
		}
	}
	
	private function getBodyMerchantErrorMessage(SimpleXMLElement $xml)
	{
		if(isset($xml->Body[0]->MerchantErrorMessage))
		{
			return (string)$xml->Body[0]->MerchantErrorMessage;
		}
		return false;
	}
}