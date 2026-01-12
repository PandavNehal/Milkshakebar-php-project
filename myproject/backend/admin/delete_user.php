<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../../index.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id");
header("Location: manage_users.php");
exit;
?>
