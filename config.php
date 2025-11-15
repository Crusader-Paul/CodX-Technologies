<?php
// config.php - update with your DB credentials
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'codx_db';

function db_connect(){
    global $DB_HOST,$DB_USER,$DB_PASS,$DB_NAME;
    $conn = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
    if($conn->connect_error) die('DB Connect error: '.$conn->connect_error);
    $conn->set_charset('utf8mb4');
    return $conn;
}
session_start();
?>