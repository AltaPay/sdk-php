<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltapayFOpenBasedHttpUtils_AltapayNetworkProblemTest extends TestCase
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

    /**
     * Disabled due to the unstable nature of the php fopen timeout code. DHAKA DHAKA DHAKA.
     */
    public function _testRequestTimeout(): void
    {
        $this->expectException(AltapayRequestTimeoutException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://testbank.altapay.com/Sleep?time=21&',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        try {
            $this->merchantApi->login();
        } catch (Exception $exception) {
            if (!($exception instanceof AltapayRequestTimeoutException)) {
                print_r($this->logger->getLogs());
            }
            throw $exception;
        }
    }

    public function testNonXMLResponse(): void
    {
        $this->expectException(AltapayInvalidResponseException::class);

        $this->merchantApi = new AltapayMerchantAPI(
            'https://testbank.altapay.com',
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
