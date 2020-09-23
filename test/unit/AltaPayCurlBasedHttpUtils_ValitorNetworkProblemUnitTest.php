<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltaPayCurlBasedHttpUtils_AltaPayNetworkProblemUnitTest extends TestCase
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

    public function testNonXMLResponse(): void
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
