<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_POST['method'] ?? 'cash';
$pincode = $_POST['pincode'] ?? null;
$location = $_POST['location'] ?? null;

// fetch cart items
$stmtCart = $conn->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?");
$stmtCart->bind_param("i", $user_id);
$stmtCart->execute();
$resCart = $stmtCart->get_result();

if($resCart->num_rows==0){
    header("Location: ../cart.php?error=empty");
    exit;
}

$total = 0;
$items=[];
while($row=$resCart->fetch_assoc()){
    $total += $row['price']*$row['quantity'];
    $items[] = $row;
}
$stmtCart->close();

// insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id,total_price,status,payment_method,pincode,location,created_at) VALUES (?,?,?,?,?,?,NOW())");
$status='pending';
$stmt->bind_param("idssss",$user_id,$total,$status,$method,$pincode,$location);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// insert order_items
$stmtItem = $conn->prepare("INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)");
foreach($items as $it){
    $stmtItem->bind_param("iiid",$order_id,$it['product_id'],$it['quantity'],$it['price']);
    $stmtItem->execute();
}
$stmtItem->close();

// clear cart
$stmtDel = $conn->prepare("DELETE FROM cart WHERE user_id=?");
$stmtDel->bind_param("i",$user_id);
$stmtDel->execute();
$stmtDel->close();

// redirect to invoice
header("Location: ../invoice.php?order_id=".$order_id);
exit;
?>
