<?php

require_once 'core.php';

$valid = array('success' => false, 'messages' => "Error updating...");

if($_POST) {
	$productId = $_POST['productId'];
	$clientId     = $_POST['clientId'];
  $orderDeliveryDate = date('Y-m-d', strtotime($_POST['orderDeliveryDate']));
  $orderQuantity = $_POST['orderQuantity'];
  $orderRemarks = $_POST['orderRemarks'];
  if (!($productId &&
      $clientId  &&
      $orderDeliveryDate &&
      $orderQuantity &&
      $orderRemarks))
  {
    $valid['messages'] = "invalid inputs";
    echo json_encode($valid);
    return false;
  }
  $sql = "SELECT * from clients WHERE client_id = $clientId LIMIT 1";
  $result = $connect->query($sql);

  if ($result === false) {
    $valid['messages'] = "invalid client found";
    echo json_encode($valid);
    return false;
  }
  $client = $result->fetch_object();

  $sql = "SELECT * from product WHERE product_id = $productId LIMIT 1";
  $result = $connect->query($sql);
  if ($result === false) {
    $valid['messages'] = "invalid product found";
    echo json_encode($valid);
    return false;
  }

  $product = $result->fetch_object();

  $subTotal = $product->rate * $orderQuantity;
  $vatValue = $subTotal * 0.13;
  $totalAmountValue = $subTotal + $vatValue;
  $discount = 0;
  $grandTotal = $totalAmountValue - $discount;
	$sql = "INSERT INTO orders (order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status, order_status) VALUES('$orderDeliveryDate', '$client->name', '$client->contact', '$subTotal', '$vatValue', '$totalAmountValue', '$discount', '$grandTotal', 0, '$grandTotal', 2, 2, 1)";
  if($connect->query($sql) === TRUE) {
    $sql = "INSERT INTO order_item (order_id, product_id, quantity, rate, total, order_item_status) VALUES($connect->insert_id,$productId,$orderQuantity,$product->rate,$subTotal,2)";
    $connect->query($sql);
    $valid['success'] = true;
    $valid['messages'] = "orders complete";
  }
	$connect->close();

	echo json_encode($valid);

} // /if $_POST
// echo json_encode($valid);