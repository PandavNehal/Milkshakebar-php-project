<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Summary - Milkshake Bar</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #004d40;
    margin-bottom: 30px;
}

/* Cart table */
.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.cart-table th, .cart-table td {
    padding: 15px;
    text-align: center;
}

.cart-table th {
    background: #004d40;
    color: #fff;
    font-weight: normal;
}

.cart-table tr:nth-child(even) {
    background: #f9f9f9;
}

.cart-table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
}

/* Total section */
.total-container {
    margin-top: 20px;
    text-align: right;
    font-size: 18px;
}

.total-container span {
    font-weight: bold;
}

/* Success message */
.success-message {
    background: #e6ffe6;
    color: #006600;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 18px;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 10px 25px;
    background: #004d40;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
}

.btn:hover {
    background: #00796b;
}
</style>
</head>
<body>
<div class="container">
    <div class="success-message">
        🎉 Your order has been placed successfully!
    </div>

    <h1>Order Summary</h1>

    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example row (replace with PHP loop) -->
            <!-- 
            <?php foreach($cart_items as $item): ?>
            <tr>
                <td><img src="<?php echo $item['image']; ?>" alt=""></td>
                <td><?php echo $item['name']; ?></td>
                <td>₹<?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>₹<?php echo $item['price'] * $item['quantity']; ?></td>
            </tr>
            <?php endforeach; ?> 
            -->
            <tr>
                <td><img src="assets/img/mango.jpg" alt=""></td>
                <td>Mango Milkshake</td>
                <td>₹120</td>
                <td>2</td>
                <td>₹240</td>
            </tr>
            <tr>
                <td><img src="assets/img/strawberry.jpg" alt=""></td>
                <td>Strawberry Milkshake</td>
                <td>₹130</td>
                <td>1</td>
                <td>₹130</td>
            </tr>
        </tbody>
    </table>

    <div class="total-container">
        Total: <span>₹370</span>
    </div>

    <div style="text-align:center; margin-top:30px;">
        <a href="index.php" class="btn">Continue Shopping</a>
    </div>
</div>
</body>
</html>
