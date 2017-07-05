<?php
/**
 * Created by PhpStorm.
 *
 * Klarna test script: capture and update an existing order.
 *
 * User: emerson
 * Date: 7/5/17
 * Time: 12:30 PM
 */

require_once(dirname(__FILE__).'/base.php');

// CAPTURE: ======================================================================

$paymentId = "4"; // PUT A PAYMENT ID FROM A PREVIOUSLY CREATED ORDER HERE

$response = $api->captureReservation($paymentId);

if(!$response->wasSuccessful())
{
    throw new Exception($response->getErrorMessage());
}


// UPDATE ORDER: ==================================================================

/**
 * Order lines:
 */
$orderLines = array(
    array(
        'description' => 'description 1',
        'itemId' => 'id 01',
        'quantity' => -1,
        'unitPrice' => 1.1,
        'goodsType' => 'item'
    ),
    array(
        'description' => 'new item',
        'itemId' => 'new id',
        'quantity' => 1,
        'unitPrice' => 1.1,
        'goodsType' => 'item'
    )
);

$response = $api->updateOrder($paymentId, $orderLines);

if(!$response->wasSuccessful())
{
    throw new Exception($response->getErrorMessage());
}
