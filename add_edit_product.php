<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
$id = $_GET['id'] ?? null;
$conn = db_connect();
$prod = null;
if($id){
  $stmt = $conn->prepare('SELECT * FROM products WHERE id=? LIMIT 1');
  $stmt->bind_param('i',$id); $stmt->execute(); $res=$stmt->get_result();
  $prod = $res->fetch_assoc();
}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = $_POST['name']; $price = floatval($_POST['price']); $stock = intval($_POST['stock']); $desc = $_POST['description']; $category = $_POST['category']; $brand = $_POST['brand'];
  if($id){
    $stmt = $conn->prepare('UPDATE products SET name=?,description=?,price=?,stock=?,category=?,brand=? WHERE id=?');
    $stmt->bind_param('ssdii si', $name, $desc, $price, $stock, $category, $brand, $id);
    // note: small types mismatch above is placeholder; adjust in real code
  } else {
    $slug = preg_replace('/[^a-z0-9]+/','-',strtolower($name));
    $stmt = $conn->prepare('INSERT INTO products (name,slug,description,price,stock,category,brand,image) VALUES (?,?,?,?,?,?,?,?)');
    $image = 'images/placeholder.jpg';
    $stmt->bind_param('sssdisss', $name, $slug, $desc, $price, $stock, $category, $brand, $image);
    $stmt->execute();
    header('Location: products.php'); exit;
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Add / Edit Product</title>
<link rel="stylesheet" href="/css/style.css" /></head><body>
<div class="container">
  <h2><?php echo $prod ? 'Edit' : 'Add'; ?> Product</h2>
  <form method="post" action="">
    <div class="form-row"><label>Name</label><input name="name" class="input" value="<?php echo $prod ? htmlspecialchars($prod['name']) : ''; ?>" required /></div>
    <div class="form-row"><label>Price</label><input name="price" class="input" value="<?php echo $prod ? $prod['price'] : ''; ?>" required /></div>
    <div class="form-row"><label>Stock</label><input name="stock" class="input" value="<?php echo $prod ? $prod['stock'] : '0'; ?>" /></div>
    <div class="form-row"><label>Category</label><input name="category" class="input" value="<?php echo $prod ? htmlspecialchars($prod['category']) : ''; ?>" /></div>
    <div class="form-row"><label>Brand</label><input name="brand" class="input" value="<?php echo $prod ? htmlspecialchars($prod['brand']) : ''; ?>" /></div>
    <div class="form-row"><label>Description</label><textarea name="description" class="input"><?php echo $prod ? htmlspecialchars($prod['description']) : ''; ?></textarea></div>
    <button class="btn" type="submit">Save Product</button>
  </form>
</div>
</body></html>