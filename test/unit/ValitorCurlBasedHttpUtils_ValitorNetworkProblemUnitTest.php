<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorCurlBasedHttpUtils_ValitorNetworkProblemUnitTest extends TestCase
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

    public function testConnectionRefused(): void
    {
        $this->expectException(ValitorConnectionFailedException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'https://localhost:404/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    public function testNonXMLResponse(): void
    {
        $this->expectException(ValitorInvalidResponseException::class);

        $this->merchantApi = new ValitorMerchantAPI(
            'https://example.com/',
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
            'https://example.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
