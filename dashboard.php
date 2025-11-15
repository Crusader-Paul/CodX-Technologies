<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Admin Dashboard</title>
<link rel="stylesheet" href="/css/style.css" /></head><body>
<div class="container">
  <h2>Admin Dashboard</h2>
  <p><a class="btn" href="products.php">Manage Products</a> <a class="btn ghost" href="orders.php">View Orders</a></p>
</div>
</body></html>