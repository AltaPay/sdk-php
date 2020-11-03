<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltapayCurlBasedHttpUtils_AltapayNetworkProblemUnitTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;

    /** @var AltapayMerchantAPI */
    private $merchantApi;
    /** @var AltapayCurlBasedHttpUtils */
    private $httpUtils;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new AltapayCurlBasedHttpUtils(5, 3, false);
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

    public function testNonXMLResponse(): void
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
