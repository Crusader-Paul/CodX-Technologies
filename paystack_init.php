<?php
// paystack_init.php
require_once __DIR__ . '/../php/config.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header('Location: /checkout.html'); exit; }
$email = $_POST['email'] ?? '';
$amount = floatval($_POST['amount'] ?? 0) * 100;
$secret = 'PAYSTACK_SECRET_KEY_HERE';
$callback = 'https://yourdomain.com/php/payments/paystack_callback.php';
$payload = json_encode(['email'=>$email,'amount'=>intval($amount),'currency'=>'GHS','callback_url'=>$callback]);
$ch = curl_init('https://api.paystack.co/transaction/initialize');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $secret, 'Content-Type: application/json']);
$response = curl_exec($ch);
if($response === false){ header('Location: /checkout.html'); exit; }
$data = json_decode($response, true);
if(isset($data['status']) && $data['status'] === true){ header('Location: ' . $data['data']['authorization_url']); exit; }
header('Location: /checkout.html'); exit;
?>