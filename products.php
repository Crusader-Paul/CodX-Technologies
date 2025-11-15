<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
$conn = db_connect();
$search = $_GET['q'] ?? '';
$page = max(1,intval($_GET['page'] ?? 1));
$per = 10; $offset = ($page-1)*$per;
$params = [];
if($search){
    $like = '%' . $search . '%';
    $stmt = $conn->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM products WHERE name LIKE ? OR description LIKE ? OR brand LIKE ? ORDER BY created_at DESC LIMIT ?,?');
    $stmt->bind_param('sssii',$like,$like,$like,$offset,$per);
    $stmt->execute(); $res = $stmt->get_result();
} else {
    $res = $conn->query('SELECT SQL_CALC_FOUND_ROWS * FROM products ORDER BY created_at DESC LIMIT ' . intval($offset) . ',' . intval($per));
}
$totalRes = $conn->query('SELECT FOUND_ROWS() as total'); $total = ($totalRes->num_rows) ? $totalRes->fetch_assoc()['total'] : 0;
$pages = max(1, ceil($total / $per));
?>
<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Products - Admin</title>
<link rel="stylesheet" href="/css/style.css" /></head><body>
<div class="container">
  <h2>Products</h2>
  <p><a class="btn" href="add_edit_product.php">Add Product</a> <a class="btn ghost" href="products_import.php">Import CSV</a> <a class="btn ghost" href="orders.php">Orders</a></p>
  <form method="get" style="margin-bottom:12px"><input name="q" class="input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" /><button class="btn small" type="submit">Search</button></form>
  <table class="table"><thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody>
<?php while($p = $res->fetch_assoc()){ ?>
<tr><td><?php echo $p['id']; ?></td><td><?php echo htmlspecialchars($p['name']); ?></td><td>GHS <?php echo number_format($p['price'],2); ?></td><td><?php echo $p['stock']; ?></td>
<td><a class="btn small" href="add_edit_product.php?id=<?php echo $p['id']; ?>">Edit</a> <a class="btn ghost small" href="delete_product.php?id=<?php echo $p['id']; ?>">Delete</a></td></tr>
<?php } ?>
  </tbody></table>
  <div style="margin-top:12px"><?php for($i=1;$i<=$pages;$i++){ ?><a class="btn <?php if($i== $page) echo 'ghost'; ?>" href="?page=<?php echo $i; if($search) echo '&q='.urlencode($search); ?>"><?php echo $i; ?></a><?php } ?></div>
</div>
</body></html>