<?php

require_once 'core.php';
require_once 'orderDeliveryScheduling.php';
require_once 'orderScheduleHelper.php';

/**
 * @param $item
 * @return string
 */
function createPartialOrderModalBtn($item)
{
  $btn = '<button class="btn btn-default button1 editBtn" ' .
         'data-orderid="%d" data-productid="%d" ' .
         'data-toggle="modal" ' .
         'data-target="#addOrderModal">' .
         '<i class="glyphicon glyphicon-edit"></i> Edit </button>';

  return sprintf($btn, $item['order_id'], $item['product_id']);
}

$output = array('data' => array());

if (isset($_POST['po'])) {

  $OrderDelivery = new OrderDelivery($connect);
  $products = $OrderDelivery->getRequiredproductPo($_POST['po']);
  $historyHeaders = $OrderDelivery->historyAsHeader($products['schedulerDeliver']);

   $x = 1;
  $output['data'] = array_map(function($item) use($products, &$x, $historyHeaders, $connect, $OrderDelivery) {
    $prodIndex = array_search($item['product_id'], array_column($products['products'], 'product_id'));

    $retVal = [
      $x,
      $item['quantity'],
      $prodIndex === -1 ? '-' : $products['products'][$prodIndex]['product_name'],
      $item['rate'],
      $item['rate'] * $item['quantity'],
    ];

    $scheduleDelivery = deliverySchedule($connect, $_POST['po'], $item['product_id'], $item['order_id']);
    $scheds = $OrderDelivery->addQuantityPerDate($scheduleDelivery, $historyHeaders);

   foreach($historyHeaders as $date) $retVal[] = $scheds[OrderDelivery::dateKey($date['modified'])];

    $totalPartialOrderDelivery = $scheduleDelivery ? array_sum(array_column($scheduleDelivery, 'quantity')) : 0;
    $maxOrdersQuantity = maxQuantity($connect, $item['order_id'], $item['product_id']);
    $retVal[] = $maxOrdersQuantity - $totalPartialOrderDelivery;
    $retVal[] = $products['orders'][0]['remarks'];

    $retVal[] = createPartialOrderModalBtn($item);

    $x++;
    return $retVal;
  }, $products['orderItems']);

  $keys = array_column($output['data'], 2);

  $tempOrder = [];
  array_map(function($data) use($keys, &$tempOrder) {
    $tempOrder[$data[2]][] = $data;
  }, $output['data']);

  $output['data'] = [];
  foreach($tempOrder as $data) {
    $retVal = $data[0];
    $retVal[1] = $retVal[1] * count($data);
    $retVal[4] = $retVal[4] * count($data);
    $output['data'][] = $retVal;
  }

  $connect->close();

  $output["headers"] = ['#', 'Qty', 'Product name', 'Rate', 'Amount'];
 foreach($historyHeaders as $date) $output["headers"][] = $date['modified'];
  $output["headers"][] = 'pending';
  $output["headers"][] = 'remarks';
  $output["headers"][] = '';
}

echo json_encode($output);