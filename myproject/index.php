<?php 
session_start(); 
include 'backend/db.php'; 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Milkshack Bar</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <?php include 'header.php'; ?>

  <!-- Home Section -->
  <section class="home section" id="home">
    <div class="home__container container">
      <h1 class="home__title text-up">MILK SHAKE</h1>

      <div class="home__images">
        <div class="home__shape slide-show"></div>
        <img src="assets/img/choco_ball.png" alt="image" class="home__bean-2 choco_ball">
        <img src="assets/img/choco_glash.png" alt="image" class="home__coffee hidden">
        <img src="assets/img/choco_wave.png" alt="image" class="home__splash hidden">
        <img src="assets/img/choco_ball.png" alt="image" class="home__bean-1 choco_ball">
        <img src="assets/img/choco_ball.png" alt="image" class="home__ice-1 choco_ball">
        <img src="assets/img/choco_ball.png" alt="image" class="home__ice-2 choco_ball">
        <img src="assets/img/choco_ball.png" alt="image" class="home__bean-3 choco_ball">
      </div>

      <img src="assets/img/new_logo.png" alt="image" class="home__sticker text-down">

      <div class="home__data">
        <p class="home__description text-down">Order Now And Enjoy your milkshake.</p>
        <a href="#about" class="button text-down">Learn More</a>
      </div>
    </div>
  </section>

  <hr>

  <!-- Menu Section -->
  <section class="Menu" id="menu">
    <h1>Milkshake Menu</h1>

    <!-- Category Buttons -->
    <div class="categories">
      <button class="active" onclick="filterMenu('all', event)">All</button>
      <button onclick="filterMenu('fruit', event)">Fruit</button>
      <button onclick="filterMenu('chocolate', event)">Chocolate</button>
      <button onclick="filterMenu('dryfruit', event)">Dry Fruits</button>
      <button onclick="filterMenu('special', event)">Special</button>
    </div>

    <!-- Menu List -->
     <div class="products">
   <?php 
$products = $conn->query("SELECT * FROM products"); 
while($product = $products->fetch_assoc()){ 
    $category = strtolower(trim($product['category'])); // lowercase
?>
 <div class="product-card" data-product-id="<?php echo $product['id']; ?>" data-category="<?php echo $category; ?>">
  <img src="assets/img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
  <h3><?php echo $product['name']; ?></h3>
  <p><?php echo $product['description']; ?></p>
  <p>₹<?php echo $product['price']; ?></p>

  <?php if(isset($_SESSION['user_id'])){ ?>
    <div class="cart-control">
      <button class="order-btn add-btn" onclick="addToCart(this)">Add to Cart</button>
      <div class="qty-box" style="display:none;">
        <button onclick="decreaseQty(this)">-</button>
        <span class="qty">1</span>
        <button onclick="increaseQty(this)">+</button>
      </div>
    </div>
  <?php } else { ?>
    <p><a class="order-btn" href="login.php">Login to order</a></p>
  <?php } ?>
</div>


<?php } ?>
    </div>
  </section>

  <!--  Popup Modal -->
  <div id="popup" class="popup">
    <div class="popup-content">
      <p>Sorry but You cannot order more than 25 items.</p>
      <button class="ok-btn" onclick="closePopup()">OK</button>
    </div>
  </div> 

  <hr>

  <!-- About Section -->
  <section id="about" class="section about">
    <div class="about-container">
      <div class="about-img">
        <img src="assets/img/cute_shake.png" alt="cute_Milkshake">
      </div>
      <div class="about-content">
        <h2>About Us</h2>
        <p>
          Welcome to <b>Milkshake Bar</b> – the home of tasty and creamy shakes.
          Started in 2025, our dream is simple: to serve happiness in every glass!
          From classic flavors to our special mixes, we craft every shake with love.
        </p>
      </div>
    </div>
  </section>

  <hr>

  <!-- Contact Section 
  <section id="contact" class="section contact">
    <h2>Contact Us</h2>
    <p>Email: milkshakebar@gmail.com</p>
    <p>Phone: +91 79845 55170</p>
    <p>Visit Us: Milkshake Bar, Surat, India</p>

    <form class="contact-form">
      <input type="text" placeholder="Your Name" required>
      <input type="email" placeholder="Your Email" required>
      <textarea placeholder="Your Message" rows="5" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </section>-->

  <!-- Login Warning Popup -->
  <div class="form-popup" id="loginWarning">
    <div class="form-content">
      <span class="close" onclick="closeForm('loginWarning')">
        <i class="fa-solid fa-xmark"></i>
      </span>
      <h3>You cannot place an order without login or registration</h3>
      <button onclick="window.location.href='login.php'">Login</button>
      <button onclick="window.location.href='register.php'">Register</button>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="assets/script.js"></script>
</body>
</html>
