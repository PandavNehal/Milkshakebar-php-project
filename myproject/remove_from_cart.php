<?php
session_start();
include 'db.php';

if(isset($_GET['cart_id'])){
    $cart_id = $_GET['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$cart_id");
    header("Location: ../cart.php");
    exit;
}
?>
