<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){ header('Location: /checkout.html'); exit; }
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

$conn = db_connect();
$conn->autocommit(false);
try{
    // create or find user by email
    $stmt = $conn->prepare('SELECT id FROM users WHERE email=? LIMIT 1');
    $stmt->bind_param('s',$email); $stmt->execute(); $res=$stmt->get_result();
    if($row=$res->fetch_assoc()){
        $user_id = $row['id'];
    } else {
        $hash = password_hash(bin2hex(random_bytes(6)), PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (name,email,password,phone,address) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sssss',$name,$email,$hash,$phone,$address);
        $stmt->execute();
        $user_id = $stmt->insert_id;
    }

    // calculate total from session cart (fallback simple)
    $cart = $_SESSION['cart'] ?? [];
    $total = 0.00;
    foreach($cart as $pid=>$qty){
        $pstmt = $conn->prepare('SELECT price FROM products WHERE id=? LIMIT 1');
        $pstmt->bind_param('i',$pid); $pstmt->execute(); $pres=$pstmt->get_result();
        if($prow=$pres->fetch_assoc()) $total += $prow['price']*$qty;
    }
    $opstmt = $conn->prepare('INSERT INTO orders (user_id,total_amount,shipping_address) VALUES (?,?,?)');
    $opstmt->bind_param('ids',$user_id,$total,$address);
    $opstmt->execute();
    $order_id = $opstmt->insert_id;

    foreach($cart as $pid=>$qty){
        $pstmt = $conn->prepare('SELECT price FROM products WHERE id=? LIMIT 1');
        $pstmt->bind_param('i',$pid); $pstmt->execute(); $pres=$pstmt->get_result();
        if($prow=$pres->fetch_assoc()){
            $price = $prow['price'];
            $it = $conn->prepare('INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)');
            $it->bind_param('iiid',$order_id,$pid,$qty,$price);
            $it->execute();
        }
    }
    $conn->commit();
    $_SESSION['cart'] = [];
    header('Location: /index.html');
    exit;
} catch(Exception $e){
    $conn->rollback();
    header('Location: /checkout.html');
    exit;
}
?>