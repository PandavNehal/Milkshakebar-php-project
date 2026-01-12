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
        move_uploaded_file($tmp_name, "../../assets/img/".$image);
        $conn->query("UPDATE products SET name='$name', description='$description', price='$price', image='$image' WHERE id=$id");
    } else {
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
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0fffb;
    margin: 0;
    padding: 20px;
    color: #222;
}
h1 {
    text-align: center;
    color: #00695c;
    margin-bottom: 20px;
}
a.button {
    display: inline-block;
    background: #004d40;
    color: #fff;
    padding: 8px 16px;
    margin: 5px 0 20px 0;
    text-decoration: none;
    border-radius: 6px;
}
a.button:hover {
    background: #00796b;
}
form {
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 6px;
}
textarea {
    resize: vertical;
    min-height: 80px;
}
button[type="submit"] {
    width: 100%;
    background: linear-gradient(180deg,#00a896,#00695c);
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
}
button[type="submit"]:hover {
    opacity: 0.9;
}
small {
    color: #555;
}
</style>
</head>
<body>
<h1>Edit Product</h1>
<a class="button" href="../../admin.php">Back to Admin Panel</a>
<form action="" method="POST" enctype="multipart/form-data">
    <label>Product Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

    <label>Description</label>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

    <label>Price (₹)</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

    <label>Image</label>
    <input type="file" name="image"><br>
    <small>Leave blank to keep current image</small><br>

    <button type="submit">Update Product</button>
</form>
</body>
</html>
