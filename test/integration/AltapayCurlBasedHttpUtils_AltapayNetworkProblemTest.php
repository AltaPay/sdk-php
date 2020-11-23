<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltapayCurlBasedHttpUtils_AltapayNetworkProblemTest extends TestCase
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

    public function testNoConnection(): void
    {
        $this->expectException(AltapayConnectionFailedException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'http://testgateway.altapay.com:28888/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }

    public function testRequestTimeout(): void
    {
        $this->expectException(AltapayRequestTimeoutException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://testbank.altapay.com/Sleep?time=21&',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $this->merchantApi->login();
    }

    public function testUnauthorizedResponse(): void
    {
        $this->expectException(AltapayUnauthorizedAccessException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://testgateway.altapay.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
