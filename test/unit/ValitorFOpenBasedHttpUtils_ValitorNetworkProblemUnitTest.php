<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorFOpenBasedHttpUtils_ValitorNetworkProblemUnitTest extends TestCase
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
        $this->expectException(ValitorConnectionFailedException::class);

        $this->merchantApi = new ValitorMerchantAPI(
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
