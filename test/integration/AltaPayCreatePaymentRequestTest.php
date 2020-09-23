<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AltaPayCreatePaymentRequestTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var TestConfig */
    private $config;
    /** @var AltaPayMerchantAPI */
    private $merchantApi;

    /**
     * @throws AltaPayMerchantAPIException
     */
    protected function setUp(): void
    {
        $this->config = new TestConfig();
        $this->merchantApi = new AltaPayMerchantAPI($this->config->installation, $this->config->username, $this->config->password);
        $this->merchantApi->login();
    }

    public function testCreatePaymentRequest(): void
    {
        $customerInfo = array(
            'type'               => 'private',
            'billing_firstname'  => 'John',
            'billing_lastname'   => 'Doe',
            'billing_address'    => 'Street 21 Copenhagen',
            'billing_postal'     => '1000',
            'billing_city'       => 'Copenhagen',
            'billing_region'     => 'Denmark',
            'billing_country'    => 'Denmark',
            'email'              => 'JohnDoe@example.com',
            'customer_phone'     => '20123456',
            'shipping_firstname' => 'John',
            'shipping_lastname'  => 'Doe',
            'shipping_address'   => 'Street 21 Copenhagen',
            'shipping_postal'    => '1000',
            'shipping_city'      => 'Copenhagen',
            'shipping_region'    => 'Denmark',
            'shipping_country'   => 'Denmark',
        );
        $response = $this->merchantApi->createPaymentRequest(
            $this->config->terminal,
            'testorder',
            42.00,
            $this->config->currency,
            'payment',
            $customerInfo
        );

        static::assertTrue($response->wasSuccessful());
    }

    public function testCreatePaymentRequestWithMoreData(): void
    {
        $customerInfo = array(
            'billing_postal'     => '2860',
            'billing_country'    => 'DK', // 2 character ISO-3166
            'billing_address'    => 'Rosenkæret 13',
            'billing_city'       => 'Søborg',
            'billing_region'     => null,
            'billing_firstname'  => 'Jens',
            'billing_lastname'   => 'Lyn',
            'email'              => 'testperson@mydomain.com',
            'shipping_postal'    => '2860',
            'shipping_country'   => 'DK', // 2 character ISO-3166
            'shipping_address'   => 'Rosenkæret 17',
            'shipping_city'      => 'Søborg',
            'shipping_region'    => null,
            'shipping_firstname' => 'Snej',
            'shipping_lastname'  => 'Nyl',
        ); // See the documentation for further details
        $cookie = 'some cookie';
        $language = 'da';
        $config = array(
            'callback_form'           => 'http://127.0.0.1/altapaypayment/form.php', 'callback_ok' => 'http://127.0.0.1/altapaypayment/ok.php', 'callback_fail' => 'http://127.0.0.1/altapaypayment/fail.php', 'callback_redirect' => ''     // See documentation if this is needed
            , 'callback_open'         => ''         // See documentation if this is needed
            , 'callback_notification' => '', // See documentation if this is needed
        );
        $transaction_info = array('auxkey' => 'aux data'); // this can be left out.

        $response = $this->merchantApi->createPaymentRequest(
            $this->config->terminal,
            'testorder',
            42.00,
            $this->config->currency,
            'payment',
            $customerInfo,
            $cookie,
            $language,
            $config,
            $transaction_info
        );

        static::assertTrue($response->wasSuccessful(), $response->getErrorMessage());
    }
}
