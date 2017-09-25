<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());
if($_POST) {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $contact = $_POST['contact'];
  $id = $_POST['id'];

  if(!($name && $address && $contact)) {
    $valid['success'] = false;
    $valid['messages'] = "Item Already exists";

    $connect->close();

    echo json_encode($valid);

    return false;
  }

  $sql = "UPDATE clients SET name = '$name', address = '$address', contact = '$contact' WHERE client_id = $id";

  if($connect->query($sql) === TRUE) {
    $valid['success'] = true;
    $valid['messages'] = "Successfully Updated";
  } else {
    $valid['success'] = false;
    $valid['messages'] = "Error while updating the members";
  }
  $connect->close();

  echo json_encode($valid);
} // /if $_POST