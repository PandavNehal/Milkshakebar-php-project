<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
 <div class="logo">
  <a href="index.php">
    <img src="assets/img/logo.png" alt="logo img" class="logo-img">
  </a>
</div>

<!-- Nav Links -->
<ul id="nav-links" class="nav-links">
    <li><a href="#home">Home</a></li>
    <li><a href="#menu">Menu</a></li>
    <li><a href="#about">About Us</a></li>
    <!-- <li><a href="#contact">Contact Us</a></li> -->
</ul>

<!-- Right side icons -->
<div class="right-icons">
    <!-- User Icon -->
    <i class="fa-solid fa-user user-icon" onclick="toggleUserBox()"></i>
    <a href="cart.php" class="cart-wrapper">
        <i class="fa-solid fa-cart-shopping cart-icon"></i>
    </a>
    <!-- <a href="wishlist.php" class="cart-wrapper">
        <i class="fa-regular fa-heart"></i> -->
        
    </a>
    <i class="fa-solid fa-bars hamburger" onclick="toggleMenu()"></i>
</div>

<!-- Hidden User Box -->
<div class="user-box" id="user-box">
    <div class="auth-buttons">
        <?php if(!isset($_SESSION['user_id'])): ?>
            <!-- User not logged in -->
            <a href="login.php" class="btn" onclick="openForm('loginForm')">Login</a>
            <a href="register.php" class="btn" onclick="openForm('registerForm')">Register</a>
        <?php else: ?>
            <!-- User logged in -->
            <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
        <?php endif; ?>
    </div>
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="logout-btn">
        <a href="logout.php" class="btn" style="background-color:#000; color:#fff;" onclick="logoutUser()">Logout</a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
            <a href="admin.php" class="btn" style="background-color:#000; color:#fff;">Admin</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
</nav>
