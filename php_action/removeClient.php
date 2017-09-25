<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());
if($_POST) {
  $id = $_POST['id'];
  $sql = "DELETE FROM clients WHERE client_id = $id";

  if($connect->query($sql) === TRUE) {
    $valid['success'] = true;
    $valid['messages'] = "Successfully Removed";
  } else {
    $valid['success'] = false;
    $valid['messages'] = "Error while removing the members";
  }
  $connect->close();

  echo json_encode($valid);
} // /if $_POST