<?php

require_once 'core.php';
require_once 'orderScheduleHelper.php';

$valid = array('success' => false, 'messages' => "Error updating...");

if($_POST) {
  $orderId = $_POST['orderId'];
  $po = $_POST['po'];
  $productId = $_POST['productId'];
  $orderDeliveryDate = date('Y-m-d', strtotime($_POST['orderDeliveryDate']));
  $orderQuantity = $_POST['orderQuantity'];
  $orderRemarks = $_POST['orderRemarks'];

  if (!($productId && $orderId  && $po && $orderId && $orderDeliveryDate && $orderQuantity && $orderRemarks)) {
    $valid['messages'] = "invalid inputs";
    echo json_encode($valid);

    return false;
  }

  $maxQuantity = maxQuantity($connect, $orderId, $productId);
  $schedDeliveryQuantity = deliveryCount($connect, $po, $productId, $orderId);
  $schedDeliveryQuantity += $orderQuantity;

  if($schedDeliveryQuantity <= $maxQuantity) {

    $updateOrdersSql = "UPDATE orders SET remarks = '$orderRemarks' WHERE order_id = $orderId";
    $connect->query($updateOrdersSql);

    $deliveryScheduleSql = sprintf("INSERT INTO delivery_schedule (product_id, po_number, order_id, delivery_date,quantity) VALUES (%d,'%s',%d,'%s',%d)",
      $productId, $po, $orderId, $orderDeliveryDate, $orderQuantity
    );

    $connect->query($deliveryScheduleSql);
    $valid['success'] = true;
    $valid['messages'] = "orders complete";
  } else {
    $valid['success'] = false;
    $valid['messages'] = "Item to deliver is greater than required quantity";
  }

	$connect->close();

	echo json_encode($valid);

} // /if $_POST
// echo json_encode($valid);