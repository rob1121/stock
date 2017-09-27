<?php
function dd($obj) {
  die(var_dump($obj));
}


function deliverySchedule($connect, $po, $productId, $orderId) {
  $selectDeliverSchedule = sprintf("SELECT * FROM delivery_schedule WHERE po_number = '%s' AND product_id = %d AND order_id = %d", $po, $productId, $orderId);
  $deliveryScheduleResult = $connect->query($selectDeliverSchedule);
  $deliverySchedule = [];

 if ($deliveryScheduleResult)
    while($deliverySchedule[] = $deliveryScheduleResult->fetch_assoc());

 return array_filter($deliverySchedule);
}

$output = array('data' => array());
$_POST['po'] = '1';
if (isset($_POST['po'])) {
  require_once 'core.php';
  require_once 'orderDeliveryScheduling.php';

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
    $retVal[] = $products['orders'][0]['remarks'];
    $retVal[] = '<button class="btn btn-default button1 editBtn" data-orderid="' .$item['order_id'] .'" data-productid="' .$item['product_id'] .'" data-toggle="modal" data-target="#addOrderModal"> <i class="glyphicon glyphicon-edit"></i> Edit </button>';

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
  $output["headers"][] = 'remarks';
  $output["headers"][] = '';
}

echo json_encode($output);

//TODO: CHANGE LOGIC ON HISTORY HEADER