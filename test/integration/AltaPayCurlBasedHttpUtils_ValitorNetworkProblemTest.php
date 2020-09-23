<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltaPayCurlBasedHttpUtils_AltaPayNetworkProblemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ArrayCachingLogger */
    private $logger;

    /** @var AltaPayMerchantAPI */
    private $merchantApi;
    /** @var AltaPayCurlBasedHttpUtils */
    private $httpUtils;

    protected function setUp(): void
    {
        $this->logger = new ArrayCachingLogger();
        $this->httpUtils = new AltaPayCurlBasedHttpUtils(5, 3, false);
    }

    public function testNoConnection(): void
    {
        $this->expectException(AltaPayConnectionFailedException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
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
        $this->expectException(AltaPayRequestTimeoutException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
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
        $this->expectException(AltaPayUnauthorizedAccessException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
            'https://testgateway.altapay.com/',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        $response = $this->merchantApi->login();
    }
}
