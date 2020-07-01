<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorReservationOfFixedAmountTest extends TestCase
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

    public function testSimplePayment(): void
    {
        $response = $this->merchantApi->reservationOfFixedAmount(
            $this->config->terminal,
            'testorder',
            42.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );

        static::assertTrue($response->wasSuccessful());
    }

    public function testPaymentWithCustomerInfo(): void
    {
        $customerInfo = array(
            'billing_postal'    => '2860',
            'billing_country'   => 'DK', // 2 character ISO-3166
            'billing_address'   => 'Rosenkæret 13',
            'billing_city'      => 'Søborg',
            'billing_region'    => 'some region',
            'billing_firstname' => 'Kødpålæg >-) <script>alert(42);</script>',
            'billing_lastname'  => 'Lyn',
            'email'             => 'testperson@mydomain.com',
        ); // See the documentation for further details

        $response = $this->merchantApi->reservationOfFixedAmount(
            $this->config->terminal,
            'with-billing-and-transinfo-'.time(),
            42.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce',
            $customerInfo
        );

        static::assertTrue($response->wasSuccessful());
        $payment = $response->getPrimaryPayment();
        static::assertNotNull($payment);

        static::assertEquals('2860', $payment->getCustomerInfo()->getBillingAddress()->getPostalCode());
        static::assertEquals('DK', $payment->getCustomerInfo()->getBillingAddress()->getCountry());
        static::assertEquals('Rosenkæret 13', $payment->getCustomerInfo()->getBillingAddress()->getAddress());
        static::assertEquals('Søborg', $payment->getCustomerInfo()->getBillingAddress()->getCity());
        static::assertEquals('some region', $payment->getCustomerInfo()->getBillingAddress()->getRegion());
        static::assertEquals('Kødpålæg >-) <script>alert(42);</script>', $payment->getCustomerInfo()->getBillingAddress()->getFirstName());
        static::assertEquals('Lyn', $payment->getCustomerInfo()->getBillingAddress()->getLastName());
        static::assertEquals('testperson@mydomain.com', $payment->getCustomerInfo()->getEmail());
    }

    public function testPaymentWithPaymentInfo(): void
    {
        $transaction_info = array('auxkey' => 'aux data (<æøå>)', 'otherkey' => 'MyValue');

        $response = $this->merchantApi->reservationOfFixedAmount(
            $this->config->terminal,
            'with-billing-and-transinfo-'.time(),
            42.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce',
            array(),
            $transaction_info
        );

        static::assertTrue($response->wasSuccessful());
        $payment = $response->getPrimaryPayment();
        static::assertNotNull($payment);
        static::assertEquals('aux data (<æøå>)', $payment->getPaymentInfo('auxkey'));
        static::assertEquals('MyValue', $payment->getPaymentInfo('otherkey'));
    }

    public function testPaymentSchemeNameIsVisa(): void
    {
        $response = $this->merchantApi->reservationOfFixedAmount(
            $this->config->terminal,
            'testorder',
            43.00,
            $this->config->currency,
            '4111000011110000',
            '2020',
            '12',
            '123',
            'eCommerce'
        );
        $payment = $response->getPrimaryPayment();
        static::assertNotNull($payment);
        static::assertEquals('Visa', $payment->getPaymentSchemeName());
    }
}
