<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AltaPayMerchantAPITest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var AltaPayMerchantAPI */
    private $merchantAPI;

    /** @var IAltaPayCommunicationLogger&MockInterface */
    private $logger;
    /** @var IAltaPayHttpUtils&MockInterface */
    private $httpUtils;
    /** @var AltaPayHttpResponse&MockInterface */
    private $response;

    protected function setUp(): void
    {
        $this->logger = Mockery::spy(IAltaPayCommunicationLogger::class);
        $this->httpUtils = Mockery::mock(IAltaPayHttpUtils::class);
        $this->response = Mockery::mock(AltaPayHttpResponse::class);

        $this->response->shouldReceive('getConnectionResult')->andReturn(AltaPayHttpResponse::CONNECTION_OKAY);
        $this->response->shouldReceive('getContentType')->andReturn('text/xml');

        $this->merchantAPI = new AltaPayMerchantAPI('http://base.url', 'username', 'password', $this->logger, $this->httpUtils);
    }

    public function testHandlesNonXmlNicely(): void
    {
        $this->expectException(AltaPayInvalidResponseException::class);

        $this->response->shouldReceive('getHttpCode')->andReturn(200);
        $this->response->shouldReceive('getContent')->andReturn('<html>
<head><title>504 Gateway Time-out</title></head>
<body bgcolor="white">
<center><h1>504 Gateway Time-out</h1></center>
<hr><center>nginx/0.7.67</center>
</body>
</html>');
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $loginResponse = $this->merchantAPI->login();
        static::assertEquals('Error: String could not be parsed as XML', $loginResponse->getErrorMessage());
    }

    public function testNon200ReturnCodeIsHandled(): void
    {
        $this->expectException(AltaPayInvalidResponseException::class);

        $this->response->shouldReceive('getHttpCode')->andReturn(500);
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $loginResponse = $this->merchantAPI->login();
    }

    public function testUnAuthorizedReturnCodeIsHandled(): void
    {
        $this->expectException(AltaPayUnauthorizedAccessException::class);

        $this->response->shouldReceive('getHttpCode')->andReturn(401);
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $loginResponse = $this->merchantAPI->login();
    }

    public function testGetPaymentParses20110831XmlCorrectly(): void
    {
        $this->response->shouldReceive('getHttpCode')->andReturn(200);
        $this->response->shouldReceive('getContent')->andReturn(file_get_contents(dirname(__DIR__, 2).'/example/xml/20110831_get_payment.xml'));
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123', array(''));
        static::assertTrue($getPaymentResponse instanceof AltaPayGetPaymentResponse);
        static::assertTrue($getPaymentResponse->wasSuccessful());
    }

    public function testGetPaymentParses20130430XmlCorrectly(): void
    {
        $this->response->shouldReceive('getHttpCode')->andReturn(200);
        $this->response->shouldReceive('getContent')->andReturn(file_get_contents(dirname(__DIR__, 2).'/example/xml/20130430_get_payment.xml'));
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123', array(''));
        static::assertTrue($getPaymentResponse instanceof AltaPayGetPaymentResponse);
        static::assertTrue($getPaymentResponse->wasSuccessful());
    }

    public function testGetPaymentParses20130430XmlWithShopOrderIDCorrectly(): void
    {
        $this->response->shouldReceive('getHttpCode')->andReturn(200);
        $this->response->shouldReceive('getContent')->andReturn(file_get_contents(dirname(__DIR__, 2).'/example/xml/20130430_get_payment.xml'));
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('', array('shop_orderid' => 'ceae3968b13111e38a24ac162d8c2738'));
        static::assertTrue($getPaymentResponse instanceof AltaPayGetPaymentResponse);
        static::assertTrue($getPaymentResponse->wasSuccessful());
    }

    public function testGetPaymentWithNoPaymentIsNotSuccessful(): void
    {
        $this->response->shouldReceive('getHttpCode')->andReturn(200);
        $this->response->shouldReceive('getContent')->andReturn(file_get_contents(dirname(__DIR__, 2).'/example/xml/20130430_get_payment_empty.xml'));
        $this->httpUtils->shouldReceive('requestURL')->andReturn($this->response);

        $this->merchantAPI->login();
        $getPaymentResponse = $this->merchantAPI->getPayment('123', array(''));
        static::assertFalse($getPaymentResponse->wasSuccessful());
    }
}
