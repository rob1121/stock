<?php

require_once 'core.php';
$clientId = $_POST['clientId'];

$sql = "SELECT * FROM clients WHERE client_id LIKE '$clientId' LIMIT 1";
$result = $connect->query($sql);
$row = $result->fetch_assoc();

$connect->close();

echo json_encode($row);