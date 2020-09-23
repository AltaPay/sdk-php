<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltaPayFOpenBasedHttpUtils_AltaPayNetworkProblemUnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;
    /** @var AltaPayFOpenBasedHttpUtils */
    private $httpUtils;

    /** @var AltaPayMerchantAPI */
    private $merchantApi;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new AltaPayFOpenBasedHttpUtils(5, 3);
    }

    public function testConnectionRefused(): void
    {
        $this->expectException(AltaPayConnectionFailedException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
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
        $this->expectException(AltaPayConnectionFailedException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
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
        $this->expectException(AltaPayInvalidResponseException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
            'https://example.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
