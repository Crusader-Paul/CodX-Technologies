<?php
require 'php/config.php';
$csv = __DIR__ . '/admin/sample_products.csv';
if(!file_exists($csv)){ echo 'CSV not found'; exit; }
$h = fopen($csv,'r');
$row = 0; $ins = 0;
$conn = db_connect();
while(($data = fgetcsv($h,1000,',')) !== false){
    $row++; if($row==1) continue;
    list($name,$slug,$description,$price,$stock,$category,$brand,$image) = $data;
    $stmt = $conn->prepare('INSERT INTO products (name,slug,description,price,stock,category,brand,image) VALUES (?,?,?,?,?,?,?,?)');
    $stmt->bind_param('sssdisss',$name,$slug,$description,$price,$stock,$category,$brand,$image);
    if(@$stmt->execute()) $ins++;
}
fclose($h);
echo 'Inserted ' . $ins . ' products.';
?>