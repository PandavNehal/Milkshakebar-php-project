<?php
session_start();
include 'backend/db.php';

if(empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: login.php");
    exit;
}

// Fetch products
$products = $conn->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
");
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// KPI
$total_orders = $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];
$today_revenue = $conn->query("
    SELECT IFNULL(SUM(total_price),0) total 
    FROM orders WHERE DATE(created_at)=CURDATE()
")->fetch_assoc()['total'];

// ORDER LIST
$search = $_GET['search'] ?? '';
$limit = $_GET['limit'] ?? '5';

// For Add Product popup
$showNegativePricePopup = false;

// ADD PRODUCT
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);
    $cat = $_POST['category_id'];
    $img = $_FILES['image']['name'];

    // Negative price check
    if($price < 0){
        $showNegativePricePopup = true;

    } else {

        // Correct path (admin.php → assets/img folder)
        $upload_path = __DIR__ . "/assets/img/" . $img;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){

            $add = $conn->prepare("INSERT INTO products(name,description,price,image,category_id) VALUES (?,?,?,?,?)");
            $add->bind_param("ssdsi",$name,$desc,$price,$img,$cat);
            $add->execute();
            $add->close();

            header("Location: admin.php?tab=products");
            exit;

        } else {
            echo "<script>alert('Image upload failed! Check assets/img permission');</script>";
        }
    }
}


// Prepare orders query
$limit_sql = $limit === "All" ? "" : "LIMIT ".intval($limit);
$search_sql = $search ? "WHERE u.name LIKE ? OR u.email LIKE ? OR o.id LIKE ?" : "";
$sql = "
  SELECT o.id AS order_id, u.name AS user_name, u.email, 
  o.total_price, o.status, o.payment_method, o.created_at
  FROM orders o
  JOIN users u ON o.user_id = u.id
  $search_sql
  ORDER BY o.created_at DESC
  $limit_sql
";
$stmt = $conn->prepare($sql);
if($search){
    $s = "%$search%";
    $stmt->bind_param("sss",$s,$s,$s);
}
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Panel</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>

<style>
:root{
  --bg:#d9f0ee;
  --card:#ffffff;
  --text:#071818;
  --muted:#94a3b8;

  --teal:#0F827A;
  --teal-mid:#17A69E;
  --teal-light:#c2f4ef;
  --accent:rgba(15,130,122,0.12);
  
  --radius:16px;
  --sidebar-w:260px;
}

*{box-sizing:border-box}
body{margin:0;font-family:'Poppins',sans-serif;background:linear-gradient(#dff7f5,#cfe9e7);color:var(--text);min-height:100vh;display:flex;}
.sidebar{width:var(--sidebar-w);min-width:var(--sidebar-w);background:linear-gradient(#0d6e67,#0f827a);padding:22px 16px;display:flex;flex-direction:column;gap:14px;color:#fff;box-shadow:0 0 20px rgba(0,0,0,0.25);}
.brand{display:flex;align-items:center;gap:12px;margin-bottom:10px;}
.logo{width:48px;height:48px;border-radius:12px;background:#14a59c;color:#fff;display:grid;place-items:center;font-size:20px;font-weight:700;}
.nav button{background:transparent;border:0;padding:12px;border-radius:12px;font-size:16px;display:flex;align-items:center;gap:10px;font-weight:600;cursor:pointer;color:#e8ffff;}
.nav button.active{background:#14a59c;color:#fff;}
.nav button:hover{background:rgba(255,255,255,0.18);}
.main{flex:1;display:flex;flex-direction:column;}
.topbar{height:70px;background:#ffffffcc;backdrop-filter:blur(6px);border-bottom:1px solid rgba(0,0,0,0.05);display:flex;align-items:center;padding:10px 22px;font-size:22px;font-weight:700;color:var(--teal);}
.wrap{padding:22px;}
.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:22px;}
.card{background:#fff;border-radius:var(--radius);padding:25px;min-height:150px;border-left:6px solid var(--teal-mid);box-shadow:0 6px 22px rgba(0,0,0,0.10);}
.kpi .label{font-size:18px;color:var(--muted);}
.kpi .num{font-size:42px;font-weight:800;margin-top:10px;color:var(--teal);}
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{padding:12px;border-bottom:1px solid #eee;text-align:left;font-size:15px}
.badge{padding:6px 12px;border-radius:20px;font-size:14px;font-weight:600}
.badge.pending{background:#fff3d9;color:#b47d00}
.badge.completed{background:#c6f8c6;color:#0b8a1c}
.badge.canceled{background:#ffd4d4;color:#9b2b2b}
#bs{background:#0F827A;color:#fff;border-radius:20px;}
/* Popup */
.popup{position: fixed;top:50%;left:50%;transform: translate(-50%,-50%);background:#fff;border:1px solid #ccc;border-radius:10px;padding:20px 30px;box-shadow:0 6px 18px rgba(0,0,0,0.2);z-index:1000;max-width:400px;}
.popup h3{margin:0 0 12px 0;color:#b00020;font-size:18px;}
.popup .close{position:absolute;top:8px;right:12px;cursor:pointer;font-size:18px;color:#333;}
.popup-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:900;}
.b{background:#0F827A;color:#fff}
</style>
</head>

<body>

<aside class="sidebar">
  <div class="brand">
     <div class="logo">M</div>
     <div>
        <strong style="font-size:18px">Admin Panel</strong><br>
        <span style="font-size:12px;opacity:.8">Dashboard</span>
     </div>
  </div>
  <nav class="nav">
    <button class="active" data-tab="dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</button>
    <button data-tab="orders"><i class="fa-solid fa-bag-shopping"></i> Orders</button>
    <button data-tab="products"><i class="fa-solid fa-box"></i> Products</button>
    <button data-tab="addproduct"><i class="fa-solid fa-plus"></i> Add Product</button>
    <!-- <button data-tab="manageusers"><i class="fa-solid fa-users"></i> Users</button> -->
    <button onclick="location.href='index.php'"><i class="fa-solid fa-house"></i> Home</button>
    <button onclick="location.href='logout.php'"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
  </nav>
</aside>

<div class="main">
  <div class="topbar">Dashboard</div>
  <div class="wrap">

    <!-- DASHBOARD TAB -->
    <div id="dashboard" class="tab-content">
      <div class="grid">
        <div class="card kpi">
          <div class="label">Total Orders</div>
          <div class="num"><?= $total_orders ?></div>
        </div>
        <div class="card kpi">
          <div class="label">Total Users</div>
          <div class="num"><?= $total_users ?></div>
        </div>
        <div class="card kpi">
          <div class="label">Total Products</div>
          <div class="num"><?= $total_products ?></div>
        </div>
        <div class="card kpi">
          <div class="label">Today's Revenue</div>
          <div class="num">₹ <?= number_format($today_revenue,2) ?></div>
        </div>
      </div>
    </div>

    <!-- ORDERS TAB -->
    <div id="orders" class="tab-content" style="display:none">
      <div class="card">
        <h2>Orders</h2>
        <form style="margin-bottom:10px" method="GET">
          <input type="hidden" name="tab" value="orders">
          <input type="text" name="search" placeholder="Search by Order ID..." value="<?= htmlspecialchars($search) ?>">
          <button id="bs">Search</button>
        </form>
        <table>
          <tr>
            <th>ID</th><th>User</th><th>Email</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th><th>Update</th>
          </tr>
          <?php
          $where = "";
          $params = [];
          $types = "";
          if(!empty($search)){
              $where = "WHERE o.id LIKE ?";
              $params[] = $search . "%";
              $types .= "s";
          }
          $sql = "SELECT o.id AS order_id, u.name AS user_name, u.email, o.total_price, o.status, o.payment_method, o.created_at
                  FROM orders o
                  JOIN users u ON o.user_id = u.id
                  $where
                  ORDER BY o.id ASC";
          $stmt = $conn->prepare($sql);
          if(!empty($params)){
              $stmt->bind_param($types, ...$params);
          }
          $stmt->execute();
          $orders_result = $stmt->get_result();
          while($o = $orders_result->fetch_assoc()):
          ?>
          <tr>
            <td>#<?= $o['order_id'] ?></td>
            <td><?= $o['user_name'] ?></td>
            <td><?= $o['email'] ?></td>
            <td>₹ <?= $o['total_price'] ?></td>
            <td><span class="badge <?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
            <td><?= ucfirst($o['payment_method']) ?></td>
            <td><?= $o['created_at'] ?></td>
            <td>
              <?php if($o['status']=='pending'): ?>
              <form method="POST" action="backend/admin/update_order_status.php">
                <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
                <select name="status" onchange="this.form.submit()">
                  <option value="pending" <?= $o['status']=='pending'?'selected':'' ?>>Pending</option>
                  <option value="completed" <?= $o['status']=='completed'?'selected':'' ?>>Completed</option>
                  <option value="canceled" <?= $o['status']=='canceled'?'selected':'' ?>>Canceled</option>
                </select>
              </form>
              <?php else: ?>N/A<?php endif; ?>
            </td>
          </tr>
          <?php endwhile; $stmt->close(); ?>
        </table>
      </div>
    </div>

    <!-- PRODUCTS TAB -->
    <div id="products" class="tab-content" style="display:none">
      <div class="card">
        <h2>Products</h2>
        <table>
          <tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Image</th><th>Actions</th></tr>
          <?php while($p=$products->fetch_assoc()): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['name'] ?></td>
            <td>₹ <?= $p['price'] ?></td>
            <td><?= $p['category_name'] ?></td>
            <td><img src="assets/img/<?= htmlspecialchars($p['image']) ?>" width="60" alt="Product"></td>
            <td>
              <a href="backend/admin/edit_product.php?id=<?= $p['id'] ?>">Edit</a> |
              <a onclick="return confirm('Delete?')" href="backend/admin/delete_product.php?id=<?= $p['id'] ?>">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </table>
      </div>
    </div>

    <!-- ADD PRODUCT TAB -->
    <div id="addproduct" class="tab-content" style="display:none">
      <div class="card">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
          <label>Name</label>
          <input type="text" name="name" required><br><br>
          <label>Description</label>
          <textarea name="description"></textarea><br><br>
          <label>Price</label>
          <input type="number" name="price" step="10" min="10" max="1000" required><br><br>
          <label>Category</label>
          <select name="category_id" required><br><br>
            <option value="">Select</option>
            <?php $categories->data_seek(0); while($c=$categories->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
            <?php endwhile; ?>
          </select><br><br>
          <label>Image</label>
          <input type="file" name="image" required class=><br><br>
          <button name="add_product" class="b">Add Product</button>
        </form>
      </div>
    </div>

    <!-- USERS TAB
    <div id="manageusers" class="tab-content" style="display:none">
      <div class="card">
        <h2>Manage Users</h2>
        <a href="backend/admin/manage_users.php">Open User Panel</a>
      </div>
    </div> -->

  </div>
</div>

<script>
let tabs = document.querySelectorAll('.nav button[data-tab]');
let pages = document.querySelectorAll('.tab-content');

function showTab(tab){
  pages.forEach(p=>p.style.display='none');
  document.getElementById(tab).style.display='block';
  tabs.forEach(b=>b.classList.remove('active'));
  document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
}

const urlParams = new URLSearchParams(window.location.search);
const activeTab = urlParams.get('tab') || 'dashboard';
showTab(activeTab);

tabs.forEach(btn=>{ btn.onclick = () => showTab(btn.dataset.tab); });
</script>

<?php if($showNegativePricePopup): ?>
<div class="popup-overlay"></div>
<div class="popup">
    <span class="close"><i class="fas fa-times"></i></span>
    <h3>Price cannot be negative!</h3>
    <p>Please enter a valid positive price to add the product.</p>
</div>
<script>
document.querySelector('.popup .close').addEventListener('click', function(){
    document.querySelector('.popup').style.display='none';
    document.querySelector('.popup-overlay').style.display='none';
});
</script>
<?php endif; ?>

</body>
</html>
