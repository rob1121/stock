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
    $retVal = [
      $x,
      $item['quantity'],
      $products['products'][$x-1]['product_name'],
      $item['rate'],
      $item['rate'] * $item['quantity'],
    ];

    foreach($historyHeaders as $date) $retVal[] = $item[OrderDelivery::dateKey($date['modified'])];

    // $retVal[] = $item['quantity'];
    $retVal[] = '<button class="btn btn-default button1 editBtn" data-productid="<?= $item->product_id?>" data-toggle="modal" id="addOrderModalBtn" data-target="#addOrderModal"> <i class="glyphicon glyphicon-edit"></i> Edit </button>';

    $x++;
    return $retVal;
  }, $products['orderItems']);

  $connect->close();

  $output["headers"] = ['#', 'Qty', 'Product name', 'Rate', 'Amount'];
  foreach($historyHeaders as $date) $output["headers"][] = $date['modified'];
  $output["headers"][] = '';
}

echo json_encode($output);