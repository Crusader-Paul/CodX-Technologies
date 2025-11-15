<?php
require_once __DIR__ . '/../php/config.php';
$ref = $_GET['reference'] ?? '';
if(!$ref){ header('Location: /'); exit; }
// TODO: verify via Paystack /transaction/verify/{reference} and update order status
echo "Paystack callback received. Reference: " . htmlspecialchars($ref);
?>