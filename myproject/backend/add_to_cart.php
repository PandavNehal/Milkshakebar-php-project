<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    echo "Login required!";
    exit;
}

if(isset($_POST['product_id'])){
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    
    // Check if product already in cart
    $check = "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id";
    $res = $conn->query($check);
    
    if($res->num_rows > 0){
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id");
    }else{
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    
    header("Location: ../cart.php");
    exit;
}
?>
