<?php
function dd($obj) {
  die(var_dump($obj));
}

$output = array('data' => array());
if (isset($_POST['po'])) {
  require_once 'core.php';
  require_once 'orderDeliveryScheduling.php';

  $OrderDelivery = new OrderDelivery($connect);
  $product = $OrderDelivery->getRequiredproductPo($_POST['po']);

  $historyHeaders = $OrderDelivery->historyAsHeader($product['orders']);

  $products = $OrderDelivery->addQuantityPerDate($product, $historyHeaders);

   $paymentStatus = "";
   $x = 1;
  $output['data'] = array_map(function($item) use($products, &$x, $historyHeaders) {
    $prodIndex = array_search($item['product_id'], array_column($products['products'], 'product_id'));
    $retVal = [
      $x,
      $item['quantity'],
      $prodIndex === -1 ? '-' : $products['products'][$prodIndex]['product_name'],
      $item['rate'],
      $item['rate'] * $item['quantity'],
    ];

//    foreach($historyHeaders as $date) $retVal[] = $item[OrderDelivery::dateKey($date['modified'])];

    // $retVal[] = $item['quantity'];
    $retVal[] = '<button class="btn btn-default button1 editBtn" data-productid="<?= $item->product_id?>" data-toggle="modal" id="addOrderModalBtn" data-target="#addOrderModal"> <i class="glyphicon glyphicon-edit"></i> Edit </button>';

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
//  foreach($historyHeaders as $date) $output["headers"][] = $date['modified'];
  $output["headers"][] = '';
}

echo json_encode($output);

//TODO: CHANGE LOGIC ON HISTORY HEADER