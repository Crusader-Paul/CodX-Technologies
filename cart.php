<?php
require 'config.php';
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
if(!$input){ echo json_encode(['status'=>0,'message'=>'Invalid request']); exit; }
$action = $input['action'] ?? '';
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if($action==='add'){
    $pid = intval($input['product_id']);
    $qty = max(1,intval($input['quantity'] ?? 1));
    if(isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] += $qty;
    else $_SESSION['cart'][$pid] = $qty;
    echo json_encode(['status'=>1,'message'=>'Added to cart','cart'=>$_SESSION['cart']]); exit;
}
if($action==='clear'){
    $_SESSION['cart'] = [];
    echo json_encode(['status'=>1,'message'=>'Cart cleared']); exit;
}
echo json_encode(['status'=>0,'message'=>'Unknown action']);
?>