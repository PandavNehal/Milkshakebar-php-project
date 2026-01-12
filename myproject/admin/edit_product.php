<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../../index.php");
    exit;
}

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Image upload if new image selected
    if($_FILES['image']['name'] != ''){
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp_name, "../../assets/images/".$image);
        $conn->query("UPDATE products SET name='$name', description='$description', price='$price', image='$image' WHERE id=$id");
    }else{
        $conn->query("UPDATE products SET name='$name', description='$description', price='$price' WHERE id=$id");
    }
    header("Location: ../../admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<h1>Edit Product</h1>
<a href="../../admin.php">Back to Admin Panel</a>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br><br>
    <textarea name="description" required><?php echo $product['description']; ?></textarea><br><br>
    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br><br>
    <input type="file" name="image"><br>
    <small>Leave blank to keep current image</small><br><br>
    <button type="submit">Update Product</button>
</form>
</body>
</html>
