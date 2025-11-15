<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    // In production, send email or store messages
    header('Location: /contact.html');
    exit;
}
header('Location: /contact.html');
?>