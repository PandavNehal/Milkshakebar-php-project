<?php
// login.php (frontend)
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Login - Milkshake Bar</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
.close { position:absolute; right:20px; top:20px; font-size:28px; font-weight:bold; color:#333; cursor:pointer; }
.close:hover { color:red; }
.auth-container { position:relative; max-width:400px; margin:50px auto; padding:30px; border:1px solid #ccc; border-radius:10px; background:#f9f9f9; }
input, button { width:100%; padding:10px; margin:6px 0; box-sizing:border-box; }
</style>
<script>function goHome(){ window.location.href = "index.php"; }</script>
</head>
<body>
<div class="auth-container">
  <span class="close" onclick="goHome()">&times;</span>
  <h2>Login</h2>
  <?php if($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>

  <form action="backend/login.php" method="POST" autocomplete="off">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
