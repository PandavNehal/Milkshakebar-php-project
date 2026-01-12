<?php
session_start();
include 'db.php';

if(isset($_POST['cart_id'], $_POST['quantity'])){
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    if($quantity >= 1 && $quantity <= 25){
        $conn->query("UPDATE cart SET quantity=$quantity WHERE id=$cart_id");
    }
}
?>
