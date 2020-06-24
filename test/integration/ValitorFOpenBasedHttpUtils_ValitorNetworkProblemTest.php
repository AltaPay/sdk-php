<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorFOpenBasedHttpUtils_ValitorNetworkProblemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;
    private $httpUtils;

    /**
     * @var ValitorMerchantAPI
     */
    private $merchantApi;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new ValitorFOpenBasedHttpUtils(5, 3);
    }

    /**
     * @expectedException \ValitorConnectionFailedException
     */
    public function testConnectionRefused()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'http://localhost:28888/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    /**
     * @expectedException \ValitorConnectionFailedException
     */
    public function testNoConnection()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'http://testgateway.valitor.com:28888/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $this->merchantApi->login();
    }

    /**
     * @expectedException \ValitorRequestTimeoutException
     * Disabled due to the unstable nature of the php fopen timeout code. DHAKA DHAKA DHAKA
     */
    public function _testRequestTimeout()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'https://testbank.valitor.com/Sleep?time=21&',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        try {
            $this->merchantApi->login();
        } catch (Exception $exception) {
            if (!($exception instanceof ValitorRequestTimeoutException)) {
                print_r($this->logger->getLogs());
            }
            throw $exception;
        }
    }

    /**
     * @expectedException \ValitorInvalidResponseException
     */
    public function testNonXMLResponse()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'https://testbank.valitor.com',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $this->merchantApi->login();
    }

    /**
     * @expectedException \ValitorUnauthorizedAccessException
     */
    public function testUnauthorizedResponse()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'https://testgateway.valitor.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    /**
     * @expectedException \ValitorInvalidResponseException
     */
    public function testNonHTTP200Response()
    {
        $this->merchantApi = new ValitorMerchantAPI(
            'http://www.valitor.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
