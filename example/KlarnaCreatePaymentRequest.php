<?php
/**
 * Created by PhpStorm.
 *
 * Klarna test script.
 *
 * User: emerson
 * Date: 7/5/17
 * Time: 11:14 AM
 */

require_once(dirname(__FILE__).'/base.php');


// The details for the order
$terminal = 'AltaPay Klarna DK';
$orderid = 'Example_Klarna_' . time();
$amount = 5.5;
$currencyCode = 'DKK';
$paymentType = 'payment';

$customerInfo = array(
    'billing_postal'=> '6800',
    'billing_country'=> 'DK', // 2 character ISO-3166
    'billing_address'=> 'Sæffleberggate 56,1 mf',
    'billing_city'=> 'Varde',
    'billing_region'=> 'DK',
    'billing_firstname'=> 'Testperson-dk',
    'billing_lastname'=> 'Approved',
    'shipping_postal'=> '6800',
    'shipping_country'=> 'DK', // 2 character ISO-3166
    'shipping_address'=> 'Sæffleberggate 56,1 mf',
    'shipping_city'=> 'Varde',
    'shipping_region'=> 'DK',
    'shipping_firstname'=> 'Testperson-dk',
    'shipping_lastname'=> 'Approved',
    'email'=>'myuser@mymail.com',
    'username' => 'myuser',
    'customer_phone' => '20123456',
    'bank_name' => 'My Bank',
    'bank_phone' => '+45 12-34 5678'
);


/**
 * Order lines:
 */
$orderLines = array(
    array(
        'description' => 'description 1',
        'itemId' => 'id 01',
        'quantity' => 1,
        'unitPrice' => 1.1,
        'goodsType' => 'item'
    ),
    array(
        'description' => 'description 2',
        'itemId' => 'id 02',
        'quantity' => 2,
        'unitPrice' => 2.2,
        'goodsType' => 'item'
    )
);

$response = $api->createPaymentRequest(
    $terminal
    , $orderid
    , $amount
    , $currencyCode
    , $paymentType
    , $customerInfo
    , null
    , null
    , array()
    , array()
    , $orderLines
);

if(!$response->wasSuccessful())
{
    throw new Exception("Could not create the payment request: ".$response->getErrorMessage());
}

// Access the url below and use the social security number 0801363945 in the page form to complete the Klarna order
print($response->getRedirectURL());