<?php

require_once 'core.php';
function maxQuantity($connect, $orderRemarks, $orderId, $productId) {
  $selectQuantityOrderItem = sprintf("SELECT quantity, order_id, product_id from order_item WHERE order_id = %d AND product_id = %d", $orderId, $productId);
  $resultSet = $connect->query($selectQuantityOrderItem);
  $maxQuantity = 0;

  while($data = $resultSet->fetch_assoc()) $maxQuantity += (int)$data['quantity'];

  return $maxQuantity;
}

function deliveryCount($connect, $po, $productId, $orderId) {
  $selectDeliverSchedule = sprintf("SELECT SUM(quantity) as total_quantity, po_number, product_id, order_id FROM delivery_schedule WHERE po_number = '%s' AND product_id = %d AND order_id = %d LIMIT 1", $po, $productId, $orderId);
  $deliveryScheduleResult = $connect->query($selectDeliverSchedule);
 if ($deliveryScheduleResult) {
  while($deliverySchedule = $deliveryScheduleResult->fetch_assoc()) {
    return $deliverySchedule ? $deliverySchedule['total_quantity'] : 0;
  }
 } else {
  return 0;
 }
}

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

  $maxQuantity = maxQuantity($connect, $orderRemarks, $orderId, $productId);
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