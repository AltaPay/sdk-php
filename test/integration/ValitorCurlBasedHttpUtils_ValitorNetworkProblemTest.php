<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorCurlBasedHttpUtils_ValitorNetworkProblemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;

    /** @var ValitorMerchantAPI */
    private $merchantApi;
    /** @var ValitorCurlBasedHttpUtils */
    private $httpUtils;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new ValitorCurlBasedHttpUtils(5, 3, false);
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
        $response = $this->merchantApi->login();
    }

    public function testRequestTimeout(): void
    {
        $this->expectException(ValitorRequestTimeoutException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'https://testbank.valitor.com/Sleep?time=21&',
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
}
