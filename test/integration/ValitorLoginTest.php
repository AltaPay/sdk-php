<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorLoginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var TestConfig */
    private $config;
    /** @var ValitorMerchantAPI */
    private $merchantApi;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->config = new TestConfig();
        $this->merchantApi = new ValitorMerchantAPI($this->config->installation, $this->config->username, $this->config->password);
    }

    public function testSuccessfullLogin(): void
    {
        $response = $this->merchantApi->login();

        static::assertTrue($response->wasSuccessful());
    }
}
