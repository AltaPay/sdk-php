<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorCaptureReservationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var TestConfig */
    private $config;
    /** @var ValitorMerchantAPI */
    private $merchantApi;

    /**
     * @throws ValitorMerchantAPIException
     */
    protected function setUp(): void
    {
        $this->config = new TestConfig();
        $this->merchantApi = new ValitorMerchantAPI($this->config->installation, $this->config->username, $this->config->password);
        $this->merchantApi->login();
    }

    public function testCapture(): void
    {
        $testReconciliationIdentifier = 'reconrecon';
        $testInvoiceNumber = 'invoiceinvoice';
        $testOrderId = 'SomeOrderId';
        $testAmount = 42.24;
        $testSalesTax = 0.0;

        $testOrderLines = array(
            array('description' => 'SomeDescription', 'itemId' => 'KungFuBoy', 'quantity' => 1.00, 'unitPrice' => 21.12, 'taxAmount' => 0.00, 'unitCode' => 'kg', 'discount' => 0.00, 'goodsType' => 'item'),
            array('description' => 'SomeDescription', 'itemId' => 'KarateKid', 'quantity' => 1.00, 'unitPrice' => 21.12, 'taxAmount' => 0.00, 'unitCode' => 'kg', 'discount' => 0.00, 'goodsType' => 'item'),
        );

        $response = $this->merchantApi->reservationOfFixedAmount(
            $this->config->terminal,
            $testOrderId,
            $testAmount,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        static::assertTrue($response->wasSuccessful());

        $response = $this->merchantApi->captureReservation(
            $response->getPrimaryPayment()->getId(),
            $testAmount,
            $testOrderLines,
            $testSalesTax,
            $testReconciliationIdentifier,
            $testInvoiceNumber
        );

        static::assertTrue($response->wasSuccessful());
    }
}
