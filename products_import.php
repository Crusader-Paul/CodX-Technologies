<?php
require '../php/config.php';
if(empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }
$message = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['csv_file'])){
    $f = $_FILES['csv_file']['tmp_name'];
    if(($handle = fopen($f, 'r')) !== false){
        $row = 0; $inserted = 0;
        $conn = db_connect();
        while(($data = fgetcsv($handle, 1000, ',')) !== false){
            $row++;
            if($row === 1) continue;
            $name = $data[0] ?? '';
            $slug = $data[1] ?? '';
            $description = $data[2] ?? '';
            $price = floatval($data[3] ?? 0);
            $stock = intval($data[4] ?? 0);
            $category = $data[5] ?? '';
            $brand = $data[6] ?? '';
            $image = $data[7] ?? 'images/placeholder.jpg';
            if(!$name) continue;
            $stmt = $conn->prepare('INSERT INTO products (name,slug,description,price,stock,category,brand,image) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->bind_param('sssdisss', $name, $slug, $description, $price, $stock, $category, $brand, $image);
            if(@$stmt->execute()) $inserted++;
        }
        fclose($handle);
        $message = "Imported {$inserted} products.";
    } else { $message = 'Failed to open CSV file.'; }
}
?>
<!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Import Products</title>
<link rel="stylesheet" href="/css/style.css" /></head><body>
<div class="container">
  <h2>Import Products (CSV)</h2>
  <?php if($message):?><div class="card"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="form-row"><label>CSV File (header: name,slug,description,price,stock,category,brand,image)</label><input type="file" name="csv_file" required /></div>
    <button class="btn" type="submit">Upload</button>
  </form>
  <p style="color:var(--muted)">Sample CSV at /admin/sample_products.csv</p>
</div>
</body></html>