<?php

/**
 * This class represents the following data structure.
 *
 * <Transaction>
 *     <TransactionId>5</TransactionId>
 *     <AuthType>payment</AuthType>
 *     <CardStatus>Valid</CardStatus>
 *     <CreditCardToken>ce657182528301c19032840ba6682bdeb5b342d8</CreditCardToken>
 *     <CreditCardMaskedPan>555555*****5444</CreditCardMaskedPan>
 *     <IsTokenized>true</IsTokenized>
 *     <ThreeDSecureResult>Not_Attempted</ThreeDSecureResult>
 *     <BlacklistToken>9484bac14dfd5dbb27329f81dcb12ceb8ed7703e</BlacklistToken>
 *     <ShopOrderId>qoute_247</ShopOrderId>
 *     <Shop>Valitor Functional Test Shop</Shop>
 *     <Terminal>Valitor Dev Terminal</Terminal>
 *     <TransactionStatus>preauth</TransactionStatus>
 *     <MerchantCurrency>978</MerchantCurrency>
 *     <CardHolderCurrency>978</CardHolderCurrency>
 *     <ReservedAmount>14.10</ReservedAmount>
 *     <CapturedAmount>0</CapturedAmount>
 *     <RefundedAmount>0</RefundedAmount>
 *     <RecurringMaxAmount>0</RecurringMaxAmount>
 *     <CreatedDate>2012-01-06 15:23:12</CreatedDate>
 *     <UpdatedDate>2012-01-06 15:23:12</UpdatedDate>
 *     <PaymentNature>CreditCard</PaymentNature>
 *     <PaymentSource>eCommerce</PaymentSource>
 *     <PaymentNatureService name="TestAcquirer">
 *         <SupportsRelease>true</SupportsRelease>
 *         <SupportsMultipleCaptures>true</SupportsMultipleCaptures>
 *         <SupportsMultipleRefunds>true</SupportsMultipleRefunds>
 *     </PaymentNatureService>
 *     <FraudRiskScore>14</FraudRiskScore>
 *     <FraudExplanation>For the test fraud service the risk score is always equal mod 101 of the created amount for the payment</FraudExplanation>
 *     <TransactionInfo></TransactionInfo>
 *     <CustomerInfo>
 *         <UserAgent></UserAgent>
 *         <IpAddress>127.0.0.1</IpAddress>
 *     </CustomerInfo>
 *     <ReconciliationIdentifiers></ReconciliationIdentifiers>
 * </Transaction>
 */
class ValitorAPIPayment
{
    /** @var SimpleXMLElement */
    private $simpleXmlElement;

    // Remember to reflect additions within this->getCurrentXml()
    /** @var string */
    private $transactionId;
    /** @var string */
    private $uuid;
    /** @var string */
    private $authType;
    /** @var string */
    private $creditCardMaskedPan;
    /** @var string */
    private $creditCardExpiryMonth;
    /** @var string */
    private $creditCardExpiryYear;
    /** @var string */
    private $creditCardToken;
    /** @var bool */
    private $isTokenized;
    /** @var string */
    private $cardStatus;
    /** @var string */
    private $shopOrderId;
    /** @var string */
    private $shop;
    /** @var string */
    private $terminal;
    /** @var string */
    private $transactionStatus;
    /** @var string */
    private $reasonCode;
    /** @var string */
    private $currency;
    /** @var string */
    private $addressVerification;
    /** @var string */
    private $addressVerificationDescription;

    /** @var string */
    private $reservedAmount;
    /** @var string */
    private $capturedAmount;
    /** @var string */
    private $refundedAmount;
    /** @var string */
    private $recurringMaxAmount;
    /** @var string */
    private $surchargeAmount;

    /** @var string */
    private $paymentSchemeName;
    /** @var string */
    private $paymentNature;
    /** @var string */
    private $paymentSource;
    /** @var ValitorAPIPaymentNatureService */
    private $paymentNatureService;

    /** @var string */
    private $fraudRiskScore;
    /** @var string */
    private $fraudExplanation;
    /** @var string */
    private $fraudRecommendation;

    /** @var string */
    private $createdDate;
    /** @var string */
    private $updatedDate;

    // Remember to reflect additions within this->getCurrentXml()
    /** @var ValitorAPICustomerInfo */
    private $customerInfo;

    /** @var ValitorAPIPaymentInfos */
    private $paymentInfos;

    /** @var ValitorAPIReconciliationIdentifier[] */
    private $reconciliationIdentifiers = array();

    /** @var ValitorAPIChargebackEvents */
    private $chargebackEvents;
    // Remember to reflect additions within this->getCurrentXml()

    /**
     * @param SimpleXMLElement $xml
     *
     * @throws Exception
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->simpleXmlElement = $xml->saveXML();
        $this->transactionId = (string)$xml->TransactionId;
        $this->uuid = (string)$xml->PaymentId;
        $this->authType = (string)$xml->AuthType;
        $this->creditCardMaskedPan = (string)$xml->CreditCardMaskedPan;
        $this->creditCardExpiryMonth = (string)$xml->CreditCardExpiry->Month;
        $this->creditCardExpiryYear = (string)$xml->CreditCardExpiry->Year;
        $this->creditCardToken = (string)$xml->CreditCardToken;
        $this->isTokenized = (bool)$xml->IsTokenized;
        $this->cardStatus = (string)$xml->CardStatus;
        $this->shopOrderId = (string)$xml->ShopOrderId;
        $this->shop = (string)$xml->Shop;
        $this->terminal = (string)$xml->Terminal;
        $this->transactionStatus = (string)$xml->TransactionStatus;
        $this->reasonCode = (string)$xml->ReasonCode;
        $this->currency = (string)$xml->MerchantCurrency;
        $this->addressVerification = (string)$xml->AddressVerification;
        $this->addressVerificationDescription = (string)$xml->AddressVerificationDescription;

        $this->reservedAmount = (string)$xml->ReservedAmount;
        $this->capturedAmount = (string)$xml->CapturedAmount;
        $this->refundedAmount = (string)$xml->RefundedAmount;
        $this->recurringMaxAmount = (string)$xml->RecurringMaxAmount;
        $this->surchargeAmount = (string)$xml->SurchargeAmount;

        $this->createdDate = (string)$xml->CreatedDate;
        $this->updatedDate = (string)$xml->UpdatedDate;

        $this->paymentSchemeName = (string)$xml->PaymentSchemeName;
        $this->paymentNature = (string)$xml->PaymentNature;
        $this->paymentSource = (string)$xml->PaymentSource;
        $this->paymentNatureService = new ValitorAPIPaymentNatureService($xml->PaymentNatureService);

        $this->fraudRiskScore = (string)$xml->FraudRiskScore;
        $this->fraudExplanation = (string)$xml->FraudExplanation;
        $this->fraudRecommendation = (string)$xml->FraudRecommendation;

        $this->customerInfo = new ValitorAPICustomerInfo($xml->CustomerInfo);
        $this->paymentInfos = new ValitorAPIPaymentInfos($xml->PaymentInfos);
        $this->chargebackEvents = new ValitorAPIChargebackEvents($xml->ChargebackEvents);

        if (isset($xml->ReconciliationIdentifiers->ReconciliationIdentifier)) {
            foreach ($xml->ReconciliationIdentifiers->ReconciliationIdentifier as $reconXml) {
                $this->reconciliationIdentifiers[] = new ValitorAPIReconciliationIdentifier($reconXml);
            }
        }
    }

    /**
     * @return bool
     */
    public function mustBeCaptured()
    {
        return $this->capturedAmount == '0';
    }

    /**
     * @return string
     */
    public function getCurrentStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * @return bool
     */
    public function isReleased()
    {
        return $this->getCurrentStatus() == 'released';
    }

    /**
     * @return ValitorAPIReconciliationIdentifier
     */
    public function getLastReconciliationIdentifier()
    {
        return $this->reconciliationIdentifiers[count($this->reconciliationIdentifiers) - 1];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * @return string
     */
    public function getShopOrderId()
    {
        return $this->shopOrderId;
    }

    /**
     * @return string
     */
    public function getMaskedPan()
    {
        return $this->creditCardMaskedPan;
    }

    /**
     * @return string
     */
    public function getCreditCardExpiryMonth()
    {
        return $this->creditCardExpiryMonth;
    }

    /**
     * @return string
     */
    public function getCreditCardExpiryYear()
    {
        return $this->creditCardExpiryYear;
    }

    /**
     * @return string
     */
    public function getCreditCardToken()
    {
        return $this->creditCardToken;
    }

    /**
     * @return bool
     */
    public function isTokenized()
    {
        return $this->isTokenized;
    }

    /**
     * @return string
     */
    public function getCardStatus()
    {
        return $this->cardStatus;
    }

    /**
     * @return string
     */
    public function getPaymentNature()
    {
        return $this->paymentNature;
    }

    /**
     * @return string
     */
    public function getPaymentSource()
    {
        return $this->paymentSource;
    }

    /**
     * @return string
     */
    public function getPaymentSchemeName()
    {
        return $this->paymentSchemeName;
    }

    /**
     * @return ValitorAPIPaymentNatureService
     */
    public function getPaymentNatureService()
    {
        return $this->paymentNatureService;
    }

    /**
     * @return string
     */
    public function getFraudRiskScore()
    {
        return $this->fraudRiskScore;
    }

    /**
     * @return string
     */
    public function getFraudExplanation()
    {
        return $this->fraudExplanation;
    }

    /**
     * @return string
     */
    public function getFraudRecommendation()
    {
        return $this->fraudRecommendation;
    }

    /**
     * @return ValitorAPICustomerInfo
     */
    public function getCustomerInfo()
    {
        return $this->customerInfo;
    }

    /**
     * @param string $keyName
     *
     * @return string|null
     */
    public function getPaymentInfo($keyName)
    {
        return $this->paymentInfos->getInfo($keyName);
    }

    /**
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getReservedAmount()
    {
        return $this->reservedAmount;
    }

    /**
     * @return string
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * @return string
     */
    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    /**
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return string
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @return ValitorAPIChargebackEvents
     */
    public function getChargebackEvents()
    {
        return $this->chargebackEvents;
    }

    /**
     * Returns an XML representation of the payment as used to instantiate the object. It does not reflect any subsequent changes.
     *
     * @see    ValitorAPIPayment::getCurrentXml() for an up-to-date XML representation of the payment
     *
     * @return SimpleXMLElement an XML representation of the object as it was instantiated
     */
    public function getXml()
    {
        return $this->simpleXmlElement;
    }

    /**
     * Returns an up-to-date XML representation of the payment.
     *
     * @see    ValitorAPIPayment::getXml() for an XML representation of the payment as used to instantiate the object
     *
     * @return SimpleXMLElement an up-to-date XML representation of the payment
     */
    public function getCurrentXml()
    {
        $simpleXmlElement = new SimpleXMLElement('<ValitorAPIPayment></ValitorAPIPayment>');

        $simpleXmlElement->addChild('TransactionId', $this->transactionId);
        $simpleXmlElement->addChild('PaymentId', $this->uuid);
        $simpleXmlElement->addChild('AuthType', $this->authType);
        $simpleXmlElement->addChild('CreditCardMaskedPan', $this->creditCardMaskedPan);
        $creditCardExpiryXml = $simpleXmlElement->addChild('CreditCardExpiry');
        $creditCardExpiryXml->addChild('CreditCardExpiryMonth', $this->creditCardExpiryMonth);
        $creditCardExpiryXml->addChild('CreditCardExpiryYear', $this->creditCardExpiryYear);
        $simpleXmlElement->addChild('CreditCardToken', $this->creditCardToken);
        $simpleXmlElement->addChild('CardStatus', $this->cardStatus);
        $simpleXmlElement->addChild('ShopOrderId', $this->shopOrderId);
        $simpleXmlElement->addChild('Shop', $this->shop);
        $simpleXmlElement->addChild('Terminal', $this->terminal);
        $simpleXmlElement->addChild('TransactionStatus', $this->transactionStatus);
        $simpleXmlElement->addChild('ReasonCode', $this->reasonCode);
        $simpleXmlElement->addChild('MerchantCurrency', $this->currency);
        $simpleXmlElement->addChild('AddressVerification', $this->addressVerification);
        $simpleXmlElement->addChild('AddressVerificationDescription', $this->addressVerificationDescription);

        $simpleXmlElement->addChild('ReservedAmount', $this->reservedAmount);
        $simpleXmlElement->addChild('CapturedAmount', $this->capturedAmount);
        $simpleXmlElement->addChild('RefundedAmount', $this->refundedAmount);
        $simpleXmlElement->addChild('RecurringMaxAmount', $this->recurringMaxAmount);
        $simpleXmlElement->addChild('SurchargeAmount', $this->surchargeAmount);

        $simpleXmlElement->addChild('PaymentSchemeName', $this->paymentSchemeName);
        $simpleXmlElement->addChild('PaymentNature', $this->paymentNature);
        $simpleXmlElement->addChild('PaymentSource', $this->paymentSource);
        $simpleXmlElement->addChild('PaymentNatureService', $this->paymentNatureService->getXmlElement());

        $simpleXmlElement->addChild('FraudRiskScore', $this->fraudRiskScore);
        $simpleXmlElement->addChild('FraudExplanation', $this->fraudExplanation);
        $simpleXmlElement->addChild('FraudRecommendation', $this->fraudRecommendation);

        $simpleXmlElement->addChild('ValitorAPICustomerInfo', $this->customerInfo->getXmlElement());
        $simpleXmlElement->addChild('ValitorAPIPaymentInfos', $this->paymentInfos->getXmlElement());
        $simpleXmlElement->addChild('ValitorAPIChargebackEvents', $this->chargebackEvents->getXmlElement());
        $simpleXmlElement->addChild('CreatedDate', $this->getCreatedDate());
        $simpleXmlElement->addChild('UpdatedDate', $this->getUpdatedDate());

        return $simpleXmlElement;
    }

    /**
     * @return string
     */
    public function getSurchargeAmount()
    {
        return $this->surchargeAmount;
    }

    /**
     * @return string
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Gives the amount of the good(s) without surcharge.
     *
     * @return string
     */
    public function getInitiallyAmount()
    {
        return number_format((float)$this->reservedAmount - (float)$this->surchargeAmount, 2);
    }

    /**
     * @return string
     */
    public function getAddressVerification()
    {
        return $this->addressVerification;
    }

    /**
     * @return string
     */
    public function getAddressVerificationDescription()
    {
        return $this->addressVerificationDescription;
    }
}
