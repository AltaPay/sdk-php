<?php
require_once(dirname(__FILE__) . '/../lib/bootstrap.php');

class ValitorMerchantAPITest extends MockitTestCase
{
	/** @var ValitorMerchantAPI */
	private $merchantAPI;

	/** @var Mock_IValitorCommunicationLogger */
	private $logger;
	/** @var Mock_IValitorHttpUtils */
	private $httpUtils;
	/** @var Mock_ValitorHttpResponse */
	private $response;

	public function setup()
	{
		Mockit::initMocks($this);
		$this->response->when()->getConnectionResult()->thenReturn(ValitorHttpResponse::CONNECTION_OKAY);
		$this->response->when()->getContentType()->thenReturn("text/xml");

		$this->merchantAPI = new ValitorMerchantAPI('http://base.url', 'username', 'password', $this->logger->instance(), $this->httpUtils->instance());
	}

	/**
	 * @expectedException ValitorInvalidResponseException
	 */
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

	/**
	 * @expectedException ValitorInvalidResponseException
	 */
	public function testNon200ReturnCodeIsHandled()
	{
		$this->response->when()->getHttpCode()->thenReturn(500);
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

		$loginResponse = $this->merchantAPI->login();
	}

	/**
	 * @expectedException ValitorUnauthorizedAccessException
	 */
	public function testUnAuthorizedReturnCodeIsHandled()
	{
		$this->response->when()->getHttpCode()->thenReturn(401);
		$this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

		$loginResponse = $this->merchantAPI->login();
	}


    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testGetPayment_Parses20110831XmlCorrectly()
    {
        $this->response->when()->getHttpCode()->thenReturn(200);
        $this->response->when()->getContent()->thenReturn(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/example/xml/20110831_get_payment.xml'));
        $this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123', '');
        $this->assertTrue($getPaymentResponse instanceof ValitorGetPaymentResponse);
        $this->assertTrue($getPaymentResponse->wasSuccessful());
    }

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testGetPayment_Parses20130430XmlCorrectly()
    {
        $this->response->when()->getHttpCode()->thenReturn(200);
        $this->response->when()->getContent()->thenReturn(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/example/xml/20130430_get_payment.xml'));
        $this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123', '');
        $this->assertTrue($getPaymentResponse instanceof ValitorGetPaymentResponse);
        $this->assertTrue($getPaymentResponse->wasSuccessful());
    }

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testGetPayment_Parses20130430XmlWithShopOrderIDCorrectly()
    {
        $this->response->when()->getHttpCode()->thenReturn(200);
        $this->response->when()->getContent()->thenReturn(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/example/xml/20130430_get_payment.xml'));
        $this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('', array("shop_orderid" => "ceae3968b13111e38a24ac162d8c2738"));
        $this->assertTrue($getPaymentResponse instanceof ValitorGetPaymentResponse);
        $this->assertTrue($getPaymentResponse->wasSuccessful());
    }

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorConnectionFailedException
     * @throws ValitorInvalidResponseException
     * @throws ValitorMerchantAPIException
     * @throws ValitorRequestTimeoutException
     * @throws ValitorUnauthorizedAccessException
     * @throws ValitorUnknownMerchantAPIException
     */
    public function testGetPayment_WithNoPayment_IsNotSuccessful()
    {
        $this->response->when()->getHttpCode()->thenReturn(200);
        $this->response->when()->getContent()->thenReturn(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/example/xml/20130430_get_payment_empty.xml'));
        $this->httpUtils->when()->requestURL()->thenReturn($this->response->instance());

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123','');
        $this->assertFalse($getPaymentResponse->wasSuccessful());
    }
}
