<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorFOpenBasedHttpUtils_ValitorNetworkProblemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;
    /** @var ValitorFOpenBasedHttpUtils */
    private $httpUtils;

    /** @var ValitorMerchantAPI */
    private $merchantApi;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new ValitorFOpenBasedHttpUtils(5, 3);
    }

    public function testConnectionRefused(): void
    {
        $this->expectException(ValitorConnectionFailedException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'http://localhost:28888/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    public function testNoConnection(): void
    {
        $this->expectException(ValitorConnectionFailedException::class);

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
     * Disabled due to the unstable nature of the php fopen timeout code. DHAKA DHAKA DHAKA.
     */
    public function _testRequestTimeout(): void
    {
        $this->expectException(ValitorRequestTimeoutException::class);

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

    public function testNonXMLResponse(): void
    {
        $this->expectException(ValitorInvalidResponseException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'https://testbank.valitor.com',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $this->merchantApi->login();
    }

    public function testUnauthorizedResponse(): void
    {
        $this->expectException(ValitorUnauthorizedAccessException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'https://testgateway.valitor.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    public function testNonHTTP200Response(): void
    {
        $this->expectException(ValitorInvalidResponseException::class);

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
