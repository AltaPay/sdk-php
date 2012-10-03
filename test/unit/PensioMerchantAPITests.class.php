<?php
require_once(dirname(__FILE__).'/../lib/bootstrap.php');

class PensioMerchantAPITests extends MockitTestCase
{
	/**
	 * @var PensioMerchantAPI
	 */
	private $merchantAPI;
	
	/**
	 * @var Mockit
	 */
	private $logger,$httpUtils,$response;
	
	public function setup()
	{
		$this->logger = $this->getMockit('IPensioCommunicationLogger');
		$this->httpUtils = $this->getMockit('IPensioHttpUtils');
		$this->response = $this->getMockit('PensioHttpResponse');
		
		$this->merchantAPI = new PensioMerchantAPI('http://base.url', 'username', 'password', $this->logger->instance(), $this->httpUtils->instance());
	}
	
	public function testHandlesNonXmlNicely()
	{
		$this->response->when()->getHttpCode()->thenReturn(200);
		$this->response->when()->getContent()->thenReturn('<html>
<head><title>504 Gateway Time-out</title></head>
<body bgcolor="white">
<center><h1>504 Gateway Time-out</h1></center>
<hr><center>nginx/0.7.67</center>
</body>
</html>');
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());
		
		$loginResponse = $this->merchantAPI->login();
		$this->assertEquals('Error: String could not be parsed as XML', $loginResponse->getErrorMessage());
	}

	public function testNon200ReturnCodeIsHandled()
	{
		$this->response->when()->getHttpCode()->thenReturn(500);
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());
		
		$loginResponse = $this->merchantAPI->login();
		$this->assertEquals('Unknown error', $loginResponse->getErrorMessage());
	}

	public function testUnAuthorizedReturnCodeIsHandled()
	{
		$this->response->when()->getHttpCode()->thenReturn(401);
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());
		
		$loginResponse = $this->merchantAPI->login();
		$this->assertEquals('Unauthorized Access Denied', $loginResponse->getErrorMessage());
	}


	public function testGetPaymentParsesXmlCorrectly()
	{
		$this->response->when()->getHttpCode()->thenReturn(200);
		$this->response->when()->getContent()->thenReturn(file_get_contents(dirname(dirname(dirname(__FILE__))).'/example/xml/payments.xml'));
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

		$this->merchantAPI->login();
		$getPaymentResponse = $this->merchantAPI->getPayment('123');
		throw new Exception(print_r($getPaymentResponse, true));
	}
}