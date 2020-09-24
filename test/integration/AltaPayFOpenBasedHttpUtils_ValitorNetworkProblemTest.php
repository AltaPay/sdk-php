<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltaPayFOpenBasedHttpUtils_AltaPayNetworkProblemTest extends TestCase
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

    /**
     * Disabled due to the unstable nature of the php fopen timeout code. DHAKA DHAKA DHAKA.
     */
    public function _testRequestTimeout(): void
    {
        $this->expectException(AltaPayRequestTimeoutException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
            'https://testbank.altapay.com/Sleep?time=21&',
            'username',
            'password',
            $this->logger,
            $this->httpUtils
        );
        try {
            $this->merchantApi->login();
        } catch (Exception $exception) {
            if (!($exception instanceof AltaPayRequestTimeoutException)) {
                print_r($this->logger->getLogs());
            }
            throw $exception;
        }
    }

    public function testNonXMLResponse(): void
    {
        $this->expectException(AltaPayInvalidResponseException::class);

        $this->merchantApi = new AltaPayMerchantAPI(
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
