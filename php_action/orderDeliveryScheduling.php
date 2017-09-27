<?php


/**
 * table with history date and value code section
 */



class OrderDelivery {
private $connect;
  public function __construct($connect) {
    $this->connect = $connect;
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
  static function dateKey($date) {
    return str_replace("/","_", $date);
  }


  /**
   * @param $this->connect
   * @param $product_id
   * @return array
   */
  function getOrderItem($product_id) {
    $sql = "SELECT * FROM order_item where product_id = {$product_id}";
    $order_item = $this->connect->query($sql);
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
   * @param $this->connect
   * @param $columns
   * @return array
   */
  function getOrderHistory($productIds) {
      $orderItems = [];

    if ($productIds) {
      $oprtr = count($productIds) > 1 ? 'IN' : '=';
      $match = count($productIds) > 1 ? ("(" . implode(', ', $productIds) . ")") : $productIds[0];

      $sql = "SELECT * FROM order_item JOIN orders USING(order_id) where product_id $oprtr $match";
      $orders = $this->connect->query($sql);

      if ($orders !== false)
        while ($orderItems[] = $orders->fetch_assoc());

      $orderItems = array_filter($orderItems);

      $orderItems = array_filter($orderItems, function($order) {
        return $order['order_status'] !== 2;
      });
    }

    return $orderItems;
  }

  function sumOFOrderToDeliver($item, $dates) {
    $total = 0;
    foreach($dates as $date) {
      $delivery = dateKey($date['modified']);
      $total += (int)$item->delivery;
    }

    return $total;
  }

  /**
   * @param $orderItems
   * @return array
   */
  function historyAsHeader($orderItems) {
    $headers = array_unique(array_column($orderItems, 'delivery_date'));
    sort($headers);
    return array_map(function($header) {
      return $this->monthDate($header);
    }, $headers);
  }

  /**
   * @param $products
   * @param $orderItems
   * @param $headers
   * @return array
   */
  function addQuantityPerDate($scheduleDeliveries, $headers) {
    $retVal = [];

    array_map(function($date) use(&$scheduleDeliveries, &$retVal) {
      $retVal[static::dateKey($date['modified'])] = 0;
    }, $headers);

    array_map(function($date) use(&$scheduleDeliveries, &$retVal) {
      foreach($scheduleDeliveries as $sched) {
        if ($date['orig'] === $sched['delivery_date']) {
          $retVal[static::dateKey($date['modified'])] += (int)$sched['quantity'];
        }
      }
    }, $headers);

    return $retVal;
  }

  function getProducts($productIds) {
      $data = [];

    if ($productIds) {
      $oprtr = count($productIds) > 1 ? 'IN' : '=';
      $match = count($productIds) > 1 ? ("(" . implode(', ', $productIds) . ")") : $productIds[0];
      $sql = "SELECT * FROM product WHERE product_id $oprtr $match";
      $query = $this->connect->query($sql);
      $index = 0;
      while ($item = $query->fetch_assoc()) {
        $data[] = $item;
        $orderItem = getOrderItem($item['product_id']);
        $data[$index]['amount'] = getTotal($orderItem);
        $data[$index] = (object)$data[$index];
        $index += 1;
      }
    }
    return $data;
  }

  function clients() {
    $sql = "SELECT * FROM clients";
    $query = $this->connect->query($sql);
    $data = [];
    while($data[]= $query->fetch_object());

    return array_filter($data);
  }

  function getRequiredproductPo($po) {
    $orders = $this->getOrdersByPo($po);
    $orderItems = $this->getORderItemByOrderIds(array_column($orders, 'order_id'));
    $products = $this->getProductById(array_column($orderItems, 'product_id'));
    $schedule = $this->scheduleDeliver($po, array_column($orders, 'order_id')[0], array_column($orderItems, 'product_id'));

    return [
      "orders" => $orders,
      "orderItems" => $orderItems,
      "products" => $products,
      "schedulerDeliver" => $schedule,
    ];
  }

  function getProductById($productIds) {
    $productRow = [];

    if ($productIds) {
      $oprtr = count($productIds) > 1 ? 'IN' : '=';
      $match = count($productIds) > 1 ? ("(" . implode(', ', $productIds) . ")") : $productIds[0];
      $productSql = "SELECT product_id, product_name, rate FROM product WHERE product_id $oprtr $match";
      $productResult = $this->connect->query($productSql);

      if($productResult !== false) {
        $x = 0;
        while($data = $productResult->fetch_assoc()) {
          $productRow[$x]['product_id'] = (int)$data['product_id'];
          $productRow[$x]['product_name'] = $data['product_name'];
          $productRow[$x]['rate'] = (int)$data['rate'];
          $x++;
        }
      }
    }
    return array_filter($productRow);
  }

  function getOrdersByPo($po) {
    $row = [];
    if ($po)  {
      $sql = "SELECT order_id, order_date, client_name, client_contact, payment_status, po_number, remarks FROM orders WHERE order_status = 1 AND po_number = '" . trim($po) . "'";
      $result = $this->connect->query($sql);
      if($result !== false) {
        $x = 0;
        while($data = $result->fetch_assoc()) {
          $row[$x]['order_id'] = (int)$data['order_id'];
          $row[$x]['order_date'] = $data['order_date'];
          $row[$x]['client_name'] = $data['client_name'];
          $row[$x]['client_contact'] = $data['client_contact'];
          $row[$x]['payment_status'] = (int)$data['payment_status'];
          $row[$x]['po_number'] = $data['po_number'];
          $row[$x]['remarks'] = $data['remarks'];
          $x++;
        }
      }
    }

    return array_filter($row);
  }

  function getORderItemByOrderIds($orderIds) {
    $orderItemRow = [];

    if ($orderIds) {
      $oprtr = count($orderIds) > 1 ? 'IN' : '=';
      $match = count($orderIds) > 1 ? ("(" . implode(', ', $orderIds) . ")") : $orderIds[0];
      $orderItemSql = "SELECT order_id, product_id, quantity, rate, total, order_item_status FROM order_item WHERE order_id $oprtr $match";
      $orderItemResult = $this->connect->query($orderItemSql);

      if($orderItemResult !== false) {
        $x = 0;
        while($data = $orderItemResult->fetch_assoc()) {
          $orderItemRow[$x]['order_id'] = (int)$data['order_id'];
          $orderItemRow[$x]['product_id'] = (int)$data['product_id'];
          $orderItemRow[$x]['quantity'] = (int)$data['quantity'];
          $orderItemRow[$x]['rate'] = (int)$data['rate'];
          $orderItemRow[$x]['total'] = (int)$data['total'];
          $orderItemRow[$x]['order_item_status'] = (int)$data['order_item_status'];
          $x++;
       }
      }
    }
    return array_filter($orderItemRow);
  }

  function scheduleDeliver($po, $order_id, $product_ids) {
  $selectDeliverSchedule = sprintf("SELECT * FROM delivery_schedule WHERE po_number = '%s' AND product_id IN (%s) AND order_id = %s", $po, implode(', ', $product_ids), $order_id);
  $deliveryScheduleResult = $this->connect->query($selectDeliverSchedule);
  $deliverySchedule = [];
 if ($deliveryScheduleResult)
  while($deliverySchedule[] = $deliveryScheduleResult->fetch_assoc());

 return array_filter($deliverySchedule);

  }
}
?>