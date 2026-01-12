function toggleMenu() {
  document.getElementById("nav-links").classList.toggle("show");
}

// Toggle user dropdown box
function toggleUserBox() {
  const box = document.getElementById("user-box");
  box.style.display = (box.style.display === "block") ? "none" : "block";
}

// Close user box when clicked outside
window.onclick = function(event) {
  if (!event.target.matches('.user-icon') && !event.target.closest('#user-box')) {
    document.getElementById("user-box").style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const coffee = document.querySelector(".home__coffee");
  const splash = document.querySelector(".home__splash");
  const beanEls = document.querySelectorAll(".choco_ball");
  const title = document.querySelector(".home__title");
  const downs = document.querySelectorAll(".home__sticker, .home__description, .button");

  // Step 1: cup slide down
  setTimeout(() => {
    coffee.classList.add("show");
  }, 100);

  // Step 2: wave zoom-out AFTER cup
  setTimeout(() => {
    splash.classList.add("show");
  }, 1000); // cup ni animation ~0.9s che, ena pachi wave start

  // Step 3: choco balls spread AFTER wave
  setTimeout(() => {
    beanEls.forEach((b, i) => {
      const n = beanEls.length;
      const baseAngle = -90; // straight up
      const spreadTotal = 120; // fan width
      const frac = (n === 1) ? 0 : (i / (n - 1)) - 0.5;
      const angleDeg = baseAngle + frac * spreadTotal;
      const baseDistance = 60;
      const extraPer = 12;
      const distance = baseDistance + i * extraPer;
      const rad = angleDeg * Math.PI / 180;
      const tx = Math.round(Math.cos(rad) * distance) + "px";
      const ty = Math.round(Math.sin(rad) * distance) + "px";

      b.style.setProperty("--tx", tx);
      b.style.setProperty("--ty", ty);

      setTimeout(() => {
        b.classList.add("show");
      }, i * 300); // each ball stagger 0.3s
    });
  }, 2200); // wave (~0.8s) complete thaya pachi

  // Step 4: texts AFTER choco balls
  setTimeout(() => {
    title.classList.add("show");
    downs.forEach(d => d.classList.add("show"));
  }, 3500); // ~1.3s balls pachi
});

/// Navbar logic
const navbar = document.querySelector(".navbar");
const navbarHeight = navbar.offsetHeight;
const homeLink = document.querySelector('a[href="#home"]');
const menuLink = document.querySelector('a[href="#menu"]');

// Home pr click → navbar hide + smooth scroll
homeLink.addEventListener("click", (e) => {
  e.preventDefault();
  navbar.classList.add("hide"); // hide navbar
  document.querySelector("#home").scrollIntoView({ behavior: "smooth" });
});

// Scroll niche thay to navbar show karo
window.addEventListener("scroll", () => {
  if (window.scrollY > 10) {
    navbar.classList.remove("hide");
  }
});

// Menu pr click → offset scroll (heading hide na thay)
menuLink.addEventListener("click", (e) => {
  e.preventDefault();
  const target = document.querySelector("#menu");
  const offsetTop = target.offsetTop - navbarHeight;

  window.scrollTo({
    top: offsetTop,
    behavior: "smooth"
  });
});

function filterMenu(category, event) {
    // Active button highlight
    document.querySelectorAll('.categories button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // All products fetch
    let items = document.querySelectorAll('.product-card');
    items.forEach(item => {
        if (category === 'all') {
            item.style.display = "block";
        } else {
            if (item.getAttribute("data-category") === category.toLowerCase()) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        }
    });
}


// Start order system
function startOrder(btn) {
  let box = btn.parentElement;
  box.innerHTML = `
    <div class="counter-box">
      <button onclick="decrease(this)">-</button>
      <span>1</span>
      <button onclick="increase(this)">+</button>
    </div>
  `;
}

// Increase quantity
function increase(btn) {
  let span = btn.parentElement.querySelector("span");
  let value = parseInt(span.innerText);

  if (value < 25) {
    span.innerText = value + 1;
  } else {
    showPopup(); // Show stylish popup
  }
}

// Decrease quantity
function decrease(btn) {
  let span = btn.parentElement.querySelector("span");
  let value = parseInt(span.innerText);

  if (value > 1) {
    span.innerText = value - 1;
  } else {
    // Reset to Order Now button
    let box = btn.parentElement.parentElement;
    box.innerHTML = `<button class="order-btn" onclick="startOrder(this)">Order Now</button>`;
  }
}

// Show popup
function showPopup() {
  document.getElementById("popup").style.display = "flex";
}

// Close popup
function closePopup() {
  document.getElementById("popup").style.display = "none";
}

// Show/Hide button while scrolling
window.onscroll = function() {
  let btn = document.getElementById("backToTop");
  if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
    btn.style.display = "block";
  } else {
    btn.style.display = "none";
  }
};

// ================= Registration form =================
const registerForm = document.getElementById("registerFormElement");
if(registerForm){
  registerForm.addEventListener("submit", function(e){
    e.preventDefault();
    const name = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const location = document.getElementById("regLocation").value;
    const pincode = document.getElementById("regPincode").value;

    // Validations
    if(!/^[A-Za-z ]+$/.test(name)){ alert("Name must contain only letters."); return; }
    if(!/^[^ ]+@[^ ]+\.[a-z]{2,3}$/.test(email)){ alert("Please enter a valid email address."); return; }
    if(password.length < 6){ alert("Password must be at least 6 characters long."); return; }
    if(!/^[0-9]{10}$/.test(phone)){ alert("Please enter a valid 10-digit phone number."); return; }
    if(location === ""){ alert("Please select a location."); return; }

    fetch("back-end/register.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&phone=${encodeURIComponent(phone)}&location=${encodeURIComponent(location)}&pincode=${encodeURIComponent(pincode)}`
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      if(data.toLowerCase().includes("successfully")){
        closeForm('registerForm');
        registerForm.reset();
        isLoggedIn = true;
      }
    })
    .catch(err => console.error(err));
  });
}

// ================= Login form =================
document.getElementById("loginFormElement")?.addEventListener("submit", function(e){
  e.preventDefault();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  fetch("back-end/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === "success"){
      alert(data.message);
      closeForm('loginForm');
      const userNameEl = document.getElementById("user-name"); 
      if(userNameEl) userNameEl.innerText = data.userName || "";
      isLoggedIn = true;
    } else {
      alert(data.message);
    }
  })
  .catch(err => console.error(err));
});

// ================= Logout =================
function logoutUser(){
  fetch("back-end/logout.php")
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    isLoggedIn = false;
    const userNameEl = document.getElementById("user-name");
    if(userNameEl) userNameEl.innerText = "";
    localStorage.removeItem("cart");
    updateCartIcon();
  })
  .catch(err => console.error(err));
}

function filterMenu(category, event) {
  // Active button highlight
  document.querySelectorAll('.categories button').forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');

  // All products fetch
  let items = document.querySelectorAll('.product-card');
  items.forEach(item => {
    if (category === 'all') {
      item.style.display = "block";
    } else {
      if (item.getAttribute("data-category") === category) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    }
  });
}

function updateCartBackend(productId, qty){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "backend/add_to_cart.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("product_id=" + productId + "&quantity=" + qty);
}

function addToCart(btn){
  btn.style.display = "none";
  let qtyBox = btn.nextElementSibling;
  qtyBox.style.display = "inline-flex";
  qtyBox.querySelector(".qty").innerText = 1;

  let productId = btn.closest(".product-card").dataset.productId;
  updateCartBackend(productId, 1);
}

function increaseQty(el){
  let qtySpan = el.parentElement.querySelector(".qty");
  let current = parseInt(qtySpan.innerText);
  if(current >= 25){
    showPopup();
    return;
  }
  qtySpan.innerText = current + 1;

  let productId = el.closest(".product-card").dataset.productId;
  updateCartBackend(productId, current + 1);
}

function decreaseQty(el){
  let qtySpan = el.parentElement.querySelector(".qty");
  let current = parseInt(qtySpan.innerText);
  let productId = el.closest(".product-card").dataset.productId;

  if(current <= 1){
    el.parentElement.style.display = "none";
    el.parentElement.previousElementSibling.style.display = "inline-block";
    updateCartBackend(productId, 0); // remove from cart
  } else {
    qtySpan.innerText = current - 1;
    updateCartBackend(productId, current - 1);
  }
}

function showPopup(){
  document.getElementById("popup").style.display = "flex";
}
function closePopup(){
  document.getElementById("popup").style.display = "none";
}

  window.onload = function() {
    if (window.location.hash) {
      // hash remove kari ne top par redirect
      window.location.href = window.location.pathname;
    }
  };