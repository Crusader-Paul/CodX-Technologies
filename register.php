<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if(!$name || !$email || !$password){ header('Location: /register.html'); exit; }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $conn = db_connect();
    $stmt = $conn->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
    $stmt->bind_param('sss',$name,$email,$hash);
    if($stmt->execute()){
        $_SESSION['user_id'] = $stmt->insert_id;
        header('Location: /index.html');
    } else {
        header('Location: /register.html');
    }
    exit;
}
header('Location: /register.html');
?>