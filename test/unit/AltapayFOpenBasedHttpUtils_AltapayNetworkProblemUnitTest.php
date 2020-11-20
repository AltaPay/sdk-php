<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltapayFOpenBasedHttpUtils_AltapayNetworkProblemUnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;
    /** @var AltapayFOpenBasedHttpUtils */
    private $httpUtils;

    /** @var AltapayMerchantAPI */
    private $merchantApi;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new AltapayFOpenBasedHttpUtils(5, 3);
    }

    public function testConnectionRefused(): void
    {
        $this->expectException(AltapayConnectionFailedException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://localhost:404/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    public function testNoConnection(): void
    {
        $this->expectException(AltapayConnectionFailedException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://localhost:404/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $this->merchantApi->login();
    }

    public function testNonHTTP200Response(): void
    {
        $this->expectException(AltapayInvalidResponseException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://example.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
