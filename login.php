<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conn = db_connect();
    $stmt = $conn->prepare('SELECT id,password,is_admin FROM users WHERE email=? LIMIT 1');
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = intval($row['is_admin']);
            if($_SESSION['is_admin']) header('Location: /admin/dashboard.php');
            else header('Location: /index.html');
            exit;
        }
    }
    header('Location: /login.html');
    exit;
}
header('Location: /login.html');
?>