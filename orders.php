<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
$conn = db_connect();
$res = $conn->query('SELECT o.*, u.email, u.name FROM orders o LEFT JOIN users u ON u.id=o.user_id ORDER BY o.order_date DESC');
?>
<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Orders - Admin</title>
<link rel="stylesheet" href="/css/style.css" /></head><body>
<div class="container">
  <h2>Orders</h2>
  <table class="table"><thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead><tbody>
<?php while($o=$res->fetch_assoc()){ ?>
<tr>
<td><?php echo $o['id']; ?></td>
<td><?php echo htmlspecialchars($o['name'].' ('.$o['email'].')'); ?></td>
<td>GHS <?php echo number_format($o['total_amount'],2); ?></td>
<td><?php echo htmlspecialchars($o['status']); ?></td>
<td><?php echo $o['order_date']; ?></td>
<td>
<form method="post" action="update_order_status.php" style="display:inline">
<input type="hidden" name="order_id" value="<?php echo $o['id']; ?>" />
<select name="status" class="input" style="display:inline-block;width:140px">
  <option value="Pending" <?php if($o['status']=='Pending') echo 'selected'; ?>>Pending</option>
  <option value="Processing" <?php if($o['status']=='Processing') echo 'selected'; ?>>Processing</option>
  <option value="Shipped" <?php if($o['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
  <option value="Completed" <?php if($o['status']=='Completed') echo 'selected'; ?>>Completed</option>
  <option value="Cancelled" <?php if($o['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
<button class="btn small" type="submit">Update</button>
</form>
</td>
</tr>
<?php } ?>
</tbody></table>
</div>
</body></html>