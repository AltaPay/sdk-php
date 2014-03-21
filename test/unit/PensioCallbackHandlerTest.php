<?php
require_once(dirname(__FILE__).'/../lib/bootstrap.php');

class PensioCallbackHandlerTest extends MockitTestCase
{
	/**
	 * @var PensioCallbackHandler
	 */
	private $handler;

	public function setup()
	{
		$this->handler = new PensioCallbackHandler();
	}

	public function testErrorCaseDueToTooLongCardNumber()
	{
	$xml = '<?xml version="1.0"?>
<APIResponse version="20121016">
	<Header>
		<Date>2013-10-29T02:33:15+01:00</Date>
		<Path>API/reservationOfFixedAmount</Path>
		<ErrorCode>0</ErrorCode>
		<ErrorMessage></ErrorMessage>
	</Header>
	<Body>
		<Result>Success</Result>
		<Transactions>
			<Transaction>
				<TransactionId></TransactionId>
				<AuthType>paymentAndCapture</AuthType>
				<CardStatus>NoCreditCard</CardStatus>
				<CreditCardToken></CreditCardToken>
				<CreditCardMaskedPan></CreditCardMaskedPan>
				<ThreeDSecureResult>Not_Applicable</ThreeDSecureResult>
				<BlacklistToken></BlacklistToken>
				<ShopOrderId>c8110a86403911e39a8e101f742d064a</ShopOrderId>
				<Shop>Wargaming</Shop>
				<Terminal>Wargaming CC JPY SEA</Terminal>
				<TransactionStatus>created</TransactionStatus>
				<MerchantCurrency>392</MerchantCurrency>
				<CardHolderCurrency>392</CardHolderCurrency>
				<ReservedAmount>0.00</ReservedAmount>
				<CapturedAmount>0.00</CapturedAmount>
				<RefundedAmount>0.00</RefundedAmount>
				<RecurringDefaultAmount>0.00</RecurringDefaultAmount>
				<CreatedDate>-0001-11-30 00:00:00</CreatedDate>
				<UpdatedDate>-0001-11-30 00:00:00</UpdatedDate>
				<PaymentNature>CreditCard</PaymentNature>
				<PaymentSchemeName>CreditCard</PaymentSchemeName>
				<PaymentNatureService name="">
					<SupportsRefunds>false</SupportsRefunds>
					<SupportsRelease>false</SupportsRelease>
					<SupportsMultipleCaptures>false</SupportsMultipleCaptures>
					<SupportsMultipleRefunds>false</SupportsMultipleRefunds>
				</PaymentNatureService>
				<ChargebackEvents/>
				<PaymentInfos>
					<PaymentInfo name="item_name"><![CDATA[6 500 Gold]]></PaymentInfo>
					<PaymentInfo name="original_amount"><![CDATA[2695]]></PaymentInfo>
					<PaymentInfo name="payment_method"><![CDATA[creditcard]]></PaymentInfo>
					<PaymentInfo name="signature"><![CDATA[75e4046386a835f362831169924aeed8]]></PaymentInfo>
					<PaymentInfo name="wg_server"><![CDATA[asia]]></PaymentInfo>
				</PaymentInfos>
				<CustomerInfo>
					<UserAgent>Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0; MANMJS)
					</UserAgent>
					<IpAddress>175.108.245.131</IpAddress>
					<Email><![CDATA[takut_takut1128@yahoo.co.jp]]></Email>
					<Username><![CDATA[]]></Username>
					<CustomerPhone></CustomerPhone>
					<OrganisationNumber></OrganisationNumber>
				</CustomerInfo>
				<ReconciliationIdentifiers/>
			</Transaction>
		</Transactions>
	</Body>
</APIResponse>';

		$response = $this->handler->parseXmlResponse($xml);
		print_r($response);
		$this->assertFalse($response->wasSuccessful());
	}
}