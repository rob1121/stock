<?php
function dd($obj) {
  die(var_dump($obj));
}

function maxQuantity($connect, $orderId, $productId) {
  $selectQuantityOrderItem = sprintf("SELECT quantity, order_id, product_id from order_item WHERE order_id = %d AND product_id = %d", $orderId, $productId);
  $resultSet = $connect->query($selectQuantityOrderItem);
  $maxQuantity = 0;

  while($data = $resultSet->fetch_assoc()) $maxQuantity += (int)$data['quantity'];

  return $maxQuantity;
}

function deliverySchedule($connect, $po, $productId, $orderId) {
  $selectDeliverSchedule = sprintf("SELECT * FROM delivery_schedule WHERE po_number = '%s' AND product_id = %d AND order_id = %d", $po, $productId, $orderId);
  $deliveryScheduleResult = $connect->query($selectDeliverSchedule);
  $deliverySchedule = [];

  if ($deliveryScheduleResult)
    while($deliverySchedule[] = $deliveryScheduleResult->fetch_assoc());

  return array_filter($deliverySchedule);
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