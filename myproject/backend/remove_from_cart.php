<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id']) || !isset($_GET['cart_id'])) exit;

$cart_id = (int)$_GET['cart_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$cart_id,$user_id);
$stmt->execute();
$stmt->close();

header("Location: ../cart.php");
exit;
?>
