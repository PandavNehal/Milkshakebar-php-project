<?php
session_start();
include 'backend/db.php';
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_items = $conn->query("SELECT cart.id AS cart_id, products.* , cart.quantity FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id=$user_id");
$total = 0;
while($item = $cart_items->fetch_assoc()){
    $total += $item['price'] * $item['quantity'];
}

// Place order on form submit
if($_SERVER['REQUEST_METHOD']=='POST'){
    $payment_method = $_POST['payment_method'];
    // Insert order
    $conn->query("INSERT INTO orders (user_id, total_price, status, payment_method) VALUES ($user_id, $total, 'pending', '$payment_method')");
    $order_id = $conn->insert_id;

    $cart_items2 = $conn->query("SELECT * FROM cart WHERE user_id=$user_id");
    while($item = $cart_items2->fetch_assoc()){
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $conn->query("SELECT price FROM products WHERE id=$product_id")->fetch_assoc()['price'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
    }
    // Clear cart
    $conn->query("DELETE FROM cart WHERE user_id=$user_id");
    $success = "Order placed successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Milkshake Bar</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <a href="index.php">Back to Home</a> | <a href="backend/logout.php">Logout</a>
</header>
<main>
<h1>Checkout</h1>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<table>
<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
<?php
$cart_items = $conn->query("SELECT cart.id AS cart_id, products.* , cart.quantity FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id=$user_id");
$total = 0;
while($item = $cart_items->fetch_assoc()):
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
?>
<tr>
<td><?php echo $item['name']; ?></td>
<td>₹<?php echo $item['price']; ?></td>
<td><?php echo $item['quantity']; ?></td>
<td>₹<?php echo $subtotal; ?></td>
</tr>
<?php endwhile; ?>
<tr>
<td colspan="3"><strong>Total</strong></td>
<td><strong>₹<?php echo $total; ?></strong></td>
</tr>
</table>

<form method="POST">
    <h3>Select Payment Method:</h3>
    <input type="radio" name="payment_method" value="cash" checked> Cash on Delivery
    <input type="radio" name="payment_method" value="online"> Online Payment<br><br>
    <button type="submit">Place Order</button>
</form>
</main>
</body>
</html>
