<?php


/**
 * table with history date and value code section
 */


/**
 * @param $historyHeaders
 * @param $item
 * @return mixed
 */
function getPending($historyHeaders, $item) {
    $pending = $item->quantity;
    foreach($historyHeaders as $date) {
      $key = dateKey($date['modified']);
      $pending -= $item->$key === '-' ? 0 : $item->$key;
    }

    return $pending;
}
/**
 * @param $date
 * @return array
 */
function monthDate($date) {
  $newDate = explode('-', $date);

  return [
    "modified" => "{$newDate[1]}/{$newDate[2]}",
    "orig" => $date,
  ];
}

/**
 * @param $date
 * @return mixed
 */
function dateKey($date) {
  return str_replace("/","_", $date);
}


/**
 * @param $db
 * @param $product_id
 * @return array
 */
function getOrderItem($db, $product_id) {
  $sql = "SELECT * FROM order_item where product_id = {$product_id}";
  $order_item = $db->query($sql);
  $orderItem = [];
  while ($orderItem[] = $order_item->fetch_assoc());

  return $orderItem;
}

/**
 * @param $orderItem
 * @return float|int
 */
function getTotal($orderItem) {
  $total = array_column((array)$orderItem, 'total');
  return array_sum($total);
}

/**
 * @param $db
 * @param $columns
 * @return array
 */
function getOrderHistory($db, $columns) {
  $product_ids = implode(', ', $columns);
  $sql = "SELECT * FROM order_item JOIN orders USING(order_id) where product_id IN ({$product_ids})";
  $orders = $db->query($sql);
  $orderItems = [];
  while ($orderItems[] = $orders->fetch_assoc());

  array_filter($orderItems);
//  $orderItems = array_map(function($order) {
//    $order['quantity'] =
//    return $order;
//  }, $orderItems);
  $orderItems = array_filter($orderItems, function($order) {
    return $order['order_status'] !== 2;
  });

  return $orderItems;
}

function sumOFOrderToDeliver($item, $dates) {
  $total = 0;
  foreach($dates as $date) {
    $delivery = dateKey($date['modified']);
    $total += (int)$item->$delivery;
  }

  return $total;
}

/**
 * @param $orderItems
 * @return array
 */
function historyAsHeader($orderItems) {
  $headers = array_unique(array_column($orderItems, 'order_date'));
  sort($headers);
  return array_map("monthDate", $headers);
}

/**
 * @param $products
 * @param $orderItems
 * @param $headers
 * @return array
 */
function addQuantityPerDate($products, $orderItems, $headers) {
  $products = array_map(function($item) use($orderItems, $headers) {
    $item = (array)$item;
    foreach($headers as $date) {
      $val = array_filter($orderItems, function($dv) use($item, $date) {
        return $dv['product_id'] === $item['product_id'] && $dv['order_date'] === $date['orig'];
      });
      $item[dateKey($date['modified'])] = count($val) ? array_sum(array_column($val, 'quantity')) : "-";
    }
    $item = (object)$item;

    return $item;
  }, $products);

  return $products;
}

function getProducts($db) {
  $sql = "SELECT * FROM product";
  $query = $db->query($sql);
  $data = [];
  $index = 0;
  while ($item = $query->fetch_assoc()) {
    $data[] = $item;
    $orderItem = getOrderItem($db, $item['product_id']);
    $data[$index]['amount'] = getTotal($orderItem);
    $data[$index] = (object)$data[$index];
    $index += 1;
  }
  return $data;
}

function clients($db) {
  $sql = "SELECT * FROM clients";
  $query = $db->query($sql);
  $data = [];
  while($data[]= $query->fetch_object());

  return array_filter($data);
}
?>