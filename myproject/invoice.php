<?php
session_start();
include 'backend/db.php';
if(!isset($_GET['order_id'])){ echo "Order ID missing"; exit; }
$order_id = (int)$_GET['order_id'];

// fetch order
$stmt = $conn->prepare("SELECT o.*, u.name AS user_name, u.email, u.phone FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=?");
$stmt->bind_param("i",$order_id);
$stmt->execute();
$res=$stmt->get_result();
if($res->num_rows==0){ echo "Order not found"; exit; }
$order=$res->fetch_assoc();
$stmt->close();

// fetch items
$stmt2 = $conn->prepare("SELECT oi.*,p.name AS product_name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
$stmt2->bind_param("i",$order_id);
$stmt2->execute();
$res2=$stmt2->get_result();
$items=$res2->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #<?php echo $order_id;?></title>
<style>
body, html {
    font-family: Arial, Helvetica, sans-serif;
    margin:0; padding:0;
    background:#f4f4f4;
    color:#222;
}
.invoice {
    max-width:800px;
    margin:20px auto;
    background:#fff;
    padding:20px;
    border-radius:8px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.invoice h1 { text-align:center; color:#004d40; }
.invoice p { margin:4px 0; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { padding:10px; border:1px solid #ccc; text-align:left; }
th { background:#e0f2f1; color:#004d40; }
.total-row td { font-weight:bold; }
.btn-home {
    display:block;
    width:200px;
    margin:30px auto;
    padding:10px;
    background:#004d40;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    text-align:center;
    text-decoration:none;
    font-size:16px;
}
.btn-home:hover { background:#00796b; }
</style>
</head>
<body>
<div class="invoice">
<h1>Invoice #<?php echo $order_id;?></h1>
<p><strong>User:</strong> <?php echo htmlspecialchars($order['user_name']); ?> (<?php echo htmlspecialchars($order['email']); ?>)</p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
<p><strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>

<table>
<tr><th>#</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
<?php $i=1;$grand=0; foreach($items as $it){ $sub=$it['price']*$it['quantity']; $grand+=$sub; ?>
<tr>
<td><?php echo $i++;?></td>
<td><?php echo htmlspecialchars($it['product_name']);?></td>
<td>₹<?php echo number_format($it['price'],2);?></td>
<td><?php echo (int)$it['quantity'];?></td>
<td>₹<?php echo number_format($sub,2);?></td>
</tr>
<?php } ?>
<tr class="total-row"><td colspan="4" style="text-align:right">Total</td><td>₹<?php echo number_format($grand,2);?></td></tr>
</table>
</div>

<!-- Home + Logout Button -->
<form method="post" action="logout_redirect.php">
    <button type="submit" class="btn-home">Go to Home & Logout</button>
</form>

</body>
</html>
