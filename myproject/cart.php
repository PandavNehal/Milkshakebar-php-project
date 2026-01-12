<?php 
session_start();
include 'backend/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// fetch user info
$user_stmt = $conn->prepare("SELECT name, email, phone, location, pincode FROM users WHERE id=?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_res = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();

// fetch cart items
$cart_items = $conn->query("SELECT c.id AS cart_id, p.*, c.quantity FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$user_id");

$total = 0;
$cart_count = 0;
while($item = $cart_items->fetch_assoc()){
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $cart_count += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Milkshake Bar</title>
<style>
body{font-family: Arial, sans-serif;background:#f0fffb;margin:0;padding:20px;color:#222;}
h1{text-align:center;color:#00695c;margin-bottom:20px;}
.container{display:flex;gap:20px;max-width:1200px;margin:auto;}
.left, .right{background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.left{flex:1;}
.right{flex:2;}
.user-info label{display:block;margin:6px 0;font-weight:600;}
.user-info input, .user-info select{width:100%;padding:6px;border-radius:6px;border:1px solid #ccc;margin-bottom:10px;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th, td{padding:12px;text-align:left;border-bottom:1px solid #eee;}
th{background:#e0f2f1;color:#004d40;}
button{padding:8px 16px;border:none;border-radius:6px;color:#fff;background:#004d40;cursor:pointer;margin:4px;}
button:hover{background:#00796b;}
</style>
</head>
<body>

<h1>Your Cart</h1>

<?php if($cart_count > 0): ?>
<div class="container">

    <!-- Left: User Info -->
    <div class="left user-info">
        <label>Name:</label>
        <input type="text" value="<?php echo htmlspecialchars($user_res['name']); ?>" readonly>
        
        <label>Email:</label>
        <input type="text" value="<?php echo htmlspecialchars($user_res['email']); ?>" readonly>
        
        <label>Phone:</label>
        <input type="text" value="<?php echo htmlspecialchars($user_res['phone']); ?>" readonly>
        
        <label>Location:</label>
        <select id="location">
            <option value="">--Select--</option>
            <option value="Katargam" <?php if($user_res['location']=='Katargam') echo 'selected'; ?>>Katargam</option>
            <option value="Vesu" <?php if($user_res['location']=='Vesu') echo 'selected'; ?>>Vesu</option>
            <option value="Amroli" <?php if($user_res['location']=='Amroli') echo 'selected'; ?>>Amroli</option>
            <option value="Sarthana" <?php if($user_res['location']=='Sarthana') echo 'selected'; ?>>Sarthana</option>
        </select>
        
        <label>Pincode:</label>
        <input type="text" id="pincode" value="<?php echo htmlspecialchars($user_res['pincode']); ?>" readonly>
    </div>

    <!-- Right: Cart -->
    <div class="right">
        <table>
            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
            <?php
            $cart_items->data_seek(0);
            while($item = $cart_items->fetch_assoc()):
                $subtotal = $item['price']*$item['quantity'];
            ?>
            <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>₹<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹<?php echo $subtotal; ?></td>
            <td>
                <a href="backend/remove_from_cart.php?cart_id=<?php echo $item['cart_id']; ?>" style="color:#e53935;text-decoration:none;font-weight:600;">Remove</a>
            </td>
            </tr>
            <?php endwhile; ?>
            <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>₹<?php echo $total; ?></strong></td>
            <td></td>
            </tr>
        </table>

        <h2>Choose Payment Method</h2>
        <button onclick="placeOrder('cash')">Cash</button>
        <button onclick="placeOrder('online')">Online Payment</button>
    </div>
</div>

<?php else: ?>
<p>Your cart is empty!</p>
<?php endif; ?>

<script>
let locationInput = document.getElementById('location');
let pincodeInput = document.getElementById('pincode');

const pinMap = {Katargam:'395004', Vesu:'395007', Amroli:'394107', Sarthana:'395009'};
locationInput.addEventListener('change', function(){
    let loc = locationInput.value;
    if(pinMap[loc]) pincodeInput.value = pinMap[loc];
    else pincodeInput.value = '';
});

function placeOrder(method){
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = 'backend/place_order.php';
    
    let inputMethod = document.createElement('input');
    inputMethod.type='hidden';
    inputMethod.name='method';
    inputMethod.value = method;
    form.appendChild(inputMethod);
    
    let inputPin = document.createElement('input');
    inputPin.type='hidden';
    inputPin.name='pincode';
    inputPin.value = pincodeInput.value;
    form.appendChild(inputPin);
    
    let inputLoc = document.createElement('input');
    inputLoc.type='hidden';
    inputLoc.name='location';
    inputLoc.value = locationInput.value;
    form.appendChild(inputLoc);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

</body>
</html>
