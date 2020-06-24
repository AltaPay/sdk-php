<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorLoginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var ValitorMerchantAPI */
    private $merchantApi;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->merchantApi = new ValitorMerchantAPI(VALITOR_INTEGRATION_INSTALLATION, VALITOR_INTEGRATION_USERNAME, VALITOR_INTEGRATION_PASSWORD);
    }

    /**
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws ValitorMerchantAPIException
     */
    public function testSuccessfullLogin(): void
    {
        $response = $this->merchantApi->login();

        static::assertTrue($response->wasSuccessful());
    }
}
