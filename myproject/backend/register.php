<?php
// backend/register.php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit;
}

$name     = isset($_POST['name']) ? trim($_POST['name']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone    = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$pincode  = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$name || !$email || !$phone || !$location || !$pincode || !$password) {
    header("Location: ../register.php?error=Please+fill+all+fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../register.php?error=Invalid+email");
    exit;
}

// check existing email
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check_res = $check->get_result();

if ($check_res->num_rows > 0) {
    $check->close();
    header("Location: ../register.php?error=Email+already+registered");
    exit;
}
$check->close();

// insert user
$hashed = password_hash($password, PASSWORD_DEFAULT);
$role = 'user';

$insert = $conn->prepare("INSERT INTO users (name, email, phone, location, pincode, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
$insert->bind_param("sssssss", $name, $email, $phone, $location, $pincode, $hashed, $role);

if ($insert->execute()) {
    // optional: auto-login after register
    $new_id = $insert->insert_id;
    $_SESSION['user_id'] = $new_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['role'] = $role;

    header("Location: ../index.php?registered=1");
    exit;
} else {
    header("Location: ../register.php?error=Database+error");
    exit;
}
$insert->close();
$conn->close();

