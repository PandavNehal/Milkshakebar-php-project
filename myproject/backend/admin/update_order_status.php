<?php
session_start();
include '../../db.php'; // adjust path if needed

// admin check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    // validate status
    $allowed = ['pending', 'completed', 'canceled'];
    if($order_id > 0 && in_array($status, $allowed)){
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// redirect back to orders tab
header("Location: ../../admin.php?tab=orders");
exit;

