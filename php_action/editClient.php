<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {

  $name = $_POST['name'];
  $address = $_POST['address'];
  $contact = $_POST['contact'];

  if(!($name && $address && $contact)) {
    $valid['success'] = false;
    $valid['messages'] = "Item Already exists";

    $connect->close();

    echo json_encode($valid);

    return false;
  }

  $result = $connect->query("SELECT * FROM clients WHERE name LIKE '$name', address LIKE '$address', contact LIKE '$contact'");

  if (!$result) {
    $sql = "INSERT INTO clients (name, address, contact)
    VALUES ('$name', '$address', $contact)";

    if($connect->query($sql) === TRUE) {
      $valid['success'] = true;
      $valid['messages'] = "Successfully Added";
    } else {
      $valid['success'] = false;
      $valid['messages'] = "Error while adding the members";
    }
  } else {
      $valid['success'] = false;
      $valid['messages'] = "Item Already exists";
  }

  $connect->close();

  echo json_encode($valid);

} // /if $_POST