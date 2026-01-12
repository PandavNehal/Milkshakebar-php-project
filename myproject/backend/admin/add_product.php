<?php
session_start();
include '../../backend/db.php'; // adjust path if needed

// admin check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit;
}

$errors = [];
$success = '';
$showNegativePricePopup = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');

    // basic validation
    if($name === '') $errors[] = "Product name is required.";
    if($description === '') $errors[] = "Description is required.";
    if($price === '' || !is_numeric($price)) $errors[] = "Valid price is required.";

    // negative price check
    if(is_numeric($price) && floatval($price) < 0){
        $showNegativePricePopup = true;
        $errors[] = "Price cannot be negative.";
    }

    // handle image
    if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
        $img = $_FILES['image'];
        if($img['error'] !== UPLOAD_ERR_OK){
            $errors[] = "Image upload error.";
        } else {
            $allowed = ['image/jpeg','image/png','image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $img['tmp_name']);
            finfo_close($finfo);

            if(!in_array($mime, $allowed)){
                $errors[] = "Only JPG, PNG or WEBP images allowed.";
            }
            if($img['size'] > 3 * 1024 * 1024){
                $errors[] = "Image must be smaller than 3MB.";
            }
        }
    } else {
        $errors[] = "Product image is required.";
    }

    // if no errors, move file and insert product
    if(empty($errors) && !$showNegativePricePopup){
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/','_', pathinfo($img['name'], PATHINFO_FILENAME));
        $newFileName = $safeName . '_' . time() . '.' . $ext;

        // Correct path: from backend/admin to project_root/assets/img/
        $uploadDir = __DIR__ . '/../../assets/img/';

        if(!is_dir($uploadDir)){
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . $newFileName;
        if(!move_uploaded_file($img['tmp_name'], $dest)){
            $errors[] = "Failed to move uploaded image. Check folder permissions: " . $uploadDir;
        } else {
            // insert into DB
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            if(!$stmt){
                $errors[] = "DB prepare error: " . $conn->error;
            } else {
                $priceVal = floatval($price);
                // store relative path for front-end
                $imgPathForDB = 'assets/img/' . $newFileName;
                $stmt->bind_param("ssds", $name, $description, $priceVal, $imgPathForDB);
                if($stmt->execute()){
                    $success = "Product added successfully.";
                    $name = $description = $price = '';
                } else {
                    $errors[] = "DB execute error: " . $stmt->error;
                    @unlink($dest);
                }
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Add Product - Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#f0fffb;margin:0;padding:24px;color:#222;}
.container{max-width:720px;margin:18px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06);}
h1{color:#00695c;text-align:center;margin-bottom:18px;}
.form-row{margin-bottom:12px;}
label{display:block;margin-bottom:6px;color:#064a47;font-weight:600;}
input[type="text"], input[type="number"], textarea, input[type="file"]{
  width:100%;padding:10px;border:1px solid #d0d6d6;border-radius:8px;box-sizing:border-box;
}
textarea{min-height:100px;resize:vertical;}
button{background:linear-gradient(180deg,#00a896,#00695c);color:#fff;border:none;padding:12px 18px;border-radius:10px;cursor:pointer;font-weight:700;}
button:hover{opacity:0.95;}
.notice{padding:10px;border-radius:8px;margin-bottom:12px;}
.error{background:#ffecec;color:#b00020;border:1px solid #f5c6c6;}
.success{background:#e8fff5;color:#00695c;border:1px solid #cfeee6;}
.back-link{display:inline-block;margin-bottom:12px;color:#004d40;text-decoration:none;}
.small{font-size:13px;color:#666;margin-top:6px;}
.popup{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border:1px solid #ccc;border-radius:10px;padding:20px 30px;box-shadow:0 6px 18px rgba(0,0,0,0.2);z-index:1000;max-width:400px;}
.popup h3{margin:0 0 12px 0;color:#b00020;font-size:18px;}
.popup .close{position:absolute;top:8px;right:12px;cursor:pointer;font-size:18px;color:#333;}
.popup-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:900;}
</style>
</head>
<body>
<div class="container">
  <a class="back-link" href="../../admin.php">&larr; Back to Admin</a>
  <h1>Add Product</h1>

  <?php if(!empty($errors) && !$showNegativePricePopup): ?>
    <div class="notice error">
      <ul style="margin:0 0 0 18px;padding:0;">
        <?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="notice success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" novalidate>
    <div class="form-row">
      <label>Product Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
    </div>

    <div class="form-row">
      <label>Description</label>
      <textarea name="description" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
    </div>

    <div class="form-row">
      <label>Price (₹)</label>
      <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
    </div>

    <div class="form-row">
      <label>Image (JPG / PNG / WEBP, max 3MB)</label>
      <input type="file" name="image" accept="image/*" required>
      <div class="small">Uploaded image will be stored in <code>assets/img/</code></div>
    </div>

    <button type="submit">Add Product</button>
  </form>
</div>

<?php if($showNegativePricePopup): ?>
<div class="popup-overlay"></div>
<div class="popup">
    <span class="close"><i class="fas fa-times"></i></span>
    <h3>Price cannot be negative!</h3>
    <p>Please enter a valid positive price to add the product.</p>
</div>
<script>
document.querySelector('.popup .close').addEventListener('click', function(){
    document.querySelector('.popup').style.display='none';
    document.querySelector('.popup-overlay').style.display='none';
});
</script>
<?php endif; ?>
</body>
</html>
