<?php
require 'config.php';
$conn = db_connect();
$res = $conn->query('SELECT id,name,price,category,brand,image,stock,slug FROM products ORDER BY created_at DESC LIMIT 100');
$out = [];
while($row = $res->fetch_assoc()) $out[] = $row;
header('Content-Type: application/json');
echo json_encode($out);
?>