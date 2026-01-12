<?php
session_start();
include '../../backend/db.php';

// ✅ Secure admin check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../../index.php");
    exit;
}

// Handle search & limit for Orders
$search = $_GET['search'] ?? '';
$limit = $_GET['limit'] ?? 5;
$limit_sql = ($limit === 'All') ? '' : "LIMIT ".intval($limit);

// Fetch all categories once
$categories_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");

// Fetch orders with search & limit
$search_sql = $search ? "WHERE u.name LIKE ? OR u.email LIKE ? OR o.id LIKE ?" : "";
$sql = "
    SELECT o.id AS order_id, u.name AS user_name, u.email, o.total_price, o.status, o.payment_method, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    $search_sql
    ORDER BY o.created_at DESC
    $limit_sql
";
$stmt = $conn->prepare($sql);
if($search){
    $s = "%$search%";
    $stmt->bind_param("sss",$s,$s,$s);
}
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();

// Handle Add Product form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Generate safe unique filename
    $image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['image']['name']);
    $tmp_name = $_FILES['image']['tmp_name'];

    // Correct upload path (admin → assets/img at root)
    $upload_path = dirname(__DIR__) . '/assets/img/' . $image_name;

    if(!is_dir(dirname($upload_path))){
        mkdir(dirname($upload_path), 0755, true);
    }

    if(move_uploaded_file($tmp_name, $upload_path)){
        // Insert product into DB
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $category_id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin.php");
        exit;

    } else {
        echo "<script>alert('Image upload failed! Check folder permissions. Path: " . htmlspecialchars($upload_path) . "');</script>";
    }
}
?>