<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if($order_id && $status){
        $conn = db_connect();
        $stmt = $conn->prepare('UPDATE orders SET status=? WHERE id=?');
        $stmt->bind_param('si', $status, $order_id);
        $stmt->execute();
    }
}
header('Location: orders.php');
exit;
?>