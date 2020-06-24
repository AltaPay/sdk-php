<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ValitorPaymentTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var SimpleXMLElement */
    private $xml;

    protected function setUp(): void
    {
        $this->xml = new SimpleXMLElement('
            <Transaction>
                <TransactionId>14398495</TransactionId>
                <AuthType>paymentAndCapture</AuthType>
                <CardStatus>Valid</CardStatus>
                <CreditCardExpiry>
                    <Year>2015</Year>
                    <Month>11</Month>
                </CreditCardExpiry>
                <CreditCardToken>37ad3ff596164142876df477e13336e0aeef0905</CreditCardToken>
                <CreditCardMaskedPan>424374******7275</CreditCardMaskedPan>
                <IsTokenized>true</IsTokenized>
                <ThreeDSecureResult>Disabled</ThreeDSecureResult>
                <CVVCheckResult>Matched</CVVCheckResult>
                <BlacklistToken>185c1c823a9b94731d9c6ba035d9b967587187bc</BlacklistToken>
                <ShopOrderId>ceae3968b82640e38a24ac162d8c2738</ShopOrderId>
                <Shop>Wargaming</Shop>
                <Terminal>Wargaming CC EUR</Terminal>
                <TransactionStatus>captured</TransactionStatus>
                <MerchantCurrency>978</MerchantCurrency>
                <CardHolderCurrency>978</CardHolderCurrency>
                <ReservedAmount>20.00</ReservedAmount>
                <CapturedAmount>19.95</CapturedAmount>
                <RefundedAmount>0.00</RefundedAmount>
                <RecurringDefaultAmount>0.00</RecurringDefaultAmount>
                <CreatedDate>2014-03-21 20:49:38</CreatedDate>
                <UpdatedDate>2014-03-21 20:49:41</UpdatedDate>
                <PaymentNature>CreditCard</PaymentNature>
                <PaymentSource>eCommerce</PaymentSource>
                <PaymentSchemeName>Visa</PaymentSchemeName>
                <PaymentNatureService name="ValitorAcquirer">
                    <SupportsRefunds>true1</SupportsRefunds>
                    <SupportsRelease>true2</SupportsRelease>
                    <SupportsMultipleCaptures>true3</SupportsMultipleCaptures>
                    <SupportsMultipleRefunds>true4</SupportsMultipleRefunds>
                </PaymentNatureService>
                <FraudRiskScore>42</FraudRiskScore>
                <FraudExplanation>For the test fraud service the risk score is always equal mod 101 of the created amount for the payment</FraudExplanation>
                <FraudRecommendation>Deny</FraudRecommendation>
                <ChargebackEvents/>
                <PaymentInfos>
                    <PaymentInfo name="item_name"><![CDATA[5 500 Gold]]></PaymentInfo>
                    <PaymentInfo name="original_amount"><![CDATA[19.95]]></PaymentInfo>
                    <PaymentInfo name="payment_method"><![CDATA[creditcard]]></PaymentInfo>
                    <PaymentInfo name="signature"><![CDATA[affe8e4f628ca55cbd07aa6b0b4fdffb]]></PaymentInfo>
                    <PaymentInfo name="wg_server"><![CDATA[eu]]></PaymentInfo>
                </PaymentInfos>
                <CustomerInfo>
                    <UserAgent>Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0</UserAgent>
                    <IpAddress>91.152.252.214</IpAddress>
                    <Email><![CDATA[timo.k.honkanen@elisanet.fi]]></Email>
                    <Username/>
                    <CustomerPhone>22 22 22 22</CustomerPhone>
                    <OrganisationNumber>345678</OrganisationNumber>
                    <CountryOfOrigin>
                        <Country>FI</Country>
                        <Source>CardNumber</Source>
                    </CountryOfOrigin>
                </CustomerInfo>
                <ReconciliationIdentifiers>
                    <ReconciliationIdentifier>
                        <Id>5c73f256-c096-43c5-8b07-e2b61c887e80</Id>
                        <Amount currency="978">19.95</Amount>
                        <Type>captured</Type>
                        <Date>2014-03-21T20:49:41+01:00</Date>
                    </ReconciliationIdentifier>
                </ReconciliationIdentifiers>
            </Transaction>');
    }

    /**
     * @throws Exception
     */
    public function testCreatedDate(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('2014-03-21 20:49:38', $payment->getCreatedDate());
    }

    /**
     * @throws Exception
     */
    public function testUpdatedDate(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('2014-03-21 20:49:41', $payment->getUpdatedDate());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfSimpleXml(): void
    {
        $xml = new SimpleXMLElement('<Transaction><PaymentNatureService /><ReconciliationIdentifiers /></Transaction>');
        $payment = new ValitorAPIPayment($xml);

        static::assertInstanceOf(ValitorAPIPayment::class, $payment);
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCurrentStatus(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('captured', $payment->getCurrentStatus());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfId(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('14398495', $payment->getId());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfAuthType(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('paymentAndCapture', $payment->getAuthType());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfShopOrderId(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('ceae3968b82640e38a24ac162d8c2738', $payment->getShopOrderId());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfMaskedPan(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('424374******7275', $payment->getMaskedPan());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCreditCardToken(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('37ad3ff596164142876df477e13336e0aeef0905', $payment->getCreditCardToken());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCardStatus(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('Valid', $payment->getCardStatus());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfPaymentNature(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('CreditCard', $payment->getPaymentNature());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfPaymentSchemeName(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('Visa', $payment->getPaymentSchemeName());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfPaymentNatureService(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        $paymentNature = $payment->getPaymentNatureService();
        static::assertEquals('ValitorAcquirer', $paymentNature->getName());
        static::assertEquals('true1', $paymentNature->getSupportsRefunds());
        static::assertEquals('true2', $paymentNature->getSupportsRelease());
        static::assertEquals('true3', $paymentNature->getSupportsMultipleCaptures());
        static::assertEquals('true4', $paymentNature->getSupportsMultipleRefunds());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfFraudRiskScore(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('42', $payment->getFraudRiskScore());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfFraudExplanation(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('For the test fraud service the risk score is always equal mod 101 of the created amount for the payment', $payment->getFraudExplanation());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfFraudRecommendation(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('Deny', $payment->getFraudRecommendation());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCustomerInfo(): void
    {
        $payment = new ValitorAPIPayment($this->xml);
        $customerInfo = $payment->getCustomerInfo();

        static::assertEquals('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0', $customerInfo->getUserAgent());
        static::assertEquals('91.152.252.214', $customerInfo->getIpAddress());
        static::assertEquals('timo.k.honkanen@elisanet.fi', $customerInfo->getEmail());
        static::assertEquals('22 22 22 22', $customerInfo->getPhone());
        static::assertEquals('345678', $customerInfo->getOrganisationNumber());
        static::assertEquals('FI', $customerInfo->getCountryOfOrigin()->getCountry());
        static::assertEquals('CardNumber', $customerInfo->getCountryOfOrigin()->getSource());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfPaymentInfo(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('5 500 Gold', $payment->getPaymentInfo('item_name'));
        static::assertEquals('19.95', $payment->getPaymentInfo('original_amount'));
        static::assertEquals('creditcard', $payment->getPaymentInfo('payment_method'));
        static::assertEquals('affe8e4f628ca55cbd07aa6b0b4fdffb', $payment->getPaymentInfo('signature'));
        static::assertEquals('eu', $payment->getPaymentInfo('wg_server'));
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCurrency(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('978', $payment->getCurrency());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfReservedAmount(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('20.00', $payment->getReservedAmount());
    }

    /**
     * @throws Exception
     */
    public function testParsingOfCapturedAmount(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertEquals('19.95', $payment->getCapturedAmount());
    }

    public function testIsTokenized(): void
    {
        $payment = new ValitorAPIPayment($this->xml);

        static::assertTrue($payment->isTokenized());
    }
}
