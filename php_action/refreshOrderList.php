<?php

require_once 'db_connect.php';
require_once 'orderDeliveryScheduling.php';

$products = getProducts($connect);
$columns = array_column($products, 'product_id');
$orderItems = getOrderHistory($connect, $columns);
$historyHeaders = historyAsHeader($orderItems);

echo json_encode([
  'headers' => array_filter($historyHeaders),
  'orders'  => array_filter($orderItems),
]);