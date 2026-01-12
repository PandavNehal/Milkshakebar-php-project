<?php
// backend/login.php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$email || !$password) {
    // redirect back with error
    header("Location: ../login.php?error=Please+enter+email+and+password");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();

    // Check hashed password OR plain-text fallback (for admin)
    if (password_verify($password, $user['password']) || $password === $user['password']) {
        // success: set session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    } else {
        header("Location: ../login.php?error=Incorrect+password");
        exit;
    }
} else {
    header("Location: ../login.php?error=User+not+found");
    exit;
}

$stmt->close();
$conn->close();
