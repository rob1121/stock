<?php

require_once 'core.php';

$valid = array('success' => false, 'messages' => array(), 'order_id' => '');
// print_r($valid);
if($_POST) {

  $orderDate        = date('Y-m-d', strtotime($_POST['orderDate']));
  $clientName       = $_POST['clientName'];
  $clientContact    = $_POST['clientContact'];
  $clientPO         = $_POST['clientPO'];
  $subTotalValue    = $_POST['subTotalValue'];
  $vatValue         =	$_POST['vatValue'];
  $totalAmountValue = $_POST['totalAmountValue'];
  $discount         = $_POST['discount'];
  $grandTotalValue  = $_POST['grandTotalValue'];
  $paid             = $_POST['paid'];
  $dueValue         = $_POST['dueValue'];
  $paymentType      = $_POST['paymentType'];
  $paymentStatus    = $_POST['paymentStatus'];

  $checkPOSql = sprintf("SELECT * from orders WHERE po_number = '%s'", $clientPO);
  if ($connect->query($checkPOSql) !== false) {
    $valid = array('success' => false, 'messages' => "PO $clientPO already exist", 'order_id' => '');
    echo json_encode($valid);
    return false;
  }

  $columns = [
    'order_date',
    'client_name',
    'client_contact',
    'sub_total',
    'vat',
    'total_amount',
    'discount',
    'grand_total',
    'paid',
    'due',
    'payment_type',
    'payment_status',
    'order_status',
    'po_number',
  ];

	$sql = sprintf(
	  "INSERT INTO orders (" . implode(", ", $columns) . ") VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, %d, '%s')",
    $orderDate,
    $clientName,
    $clientContact,
    $subTotalValue,
    $vatValue,
    $totalAmountValue,
    $discount,
    $grandTotalValue,
    $paid,
    $dueValue,
    $paymentType,
    $paymentStatus,
    1,
    $clientPO
  );

	$orderStatus = false;
	if($connect->query($sql) === true) {
		$order_id = $connect->insert_id;
		$valid['order_id'] = $order_id;

		$orderStatus = true;
	}


	// echo $_POST['productName'];
	$orderItemStatus = false;

	for($x = 0; $x < count($_POST['productName']); $x++) {
		$updateProductQuantitySql = "SELECT product_id, product.quantity FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $connect->query($updateProductQuantitySql);


		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
			$updateQuantity[$x] = $updateProductQuantityResult[1] - $_POST['quantity'][$x];
				// update product table
				$updateProductTable = "UPDATE product SET quantity = '".$updateQuantity[$x]."' WHERE product_id = ".$_POST['productName'][$x]."";
				$connect->query($updateProductTable);

				// add into order_item
				$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, rate, total, order_item_status)
				VALUES ('$order_id', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

				$connect->query($orderItemSql);
        $order_item = $connect->insert_id;
				if($x == count($_POST['productName'])) {
					$orderItemStatus = true;
				}
		} // while
	} // /for quantity

	$valid['success'] = true;
	$valid['messages'] = "Successfully Added";

	$connect->close();

	echo json_encode($valid);

} // /if $_POST
// echo json_encode($valid);