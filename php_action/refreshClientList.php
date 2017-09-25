<?php

require_once 'core.php';

$results = $connect->query("SELECT * FROM clients");
$result = [];
while($result[] = $results->fetch_assoc());

echo json_encode(array_filter($result));
?>