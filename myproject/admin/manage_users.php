<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../../index.php");
    exit;
}

$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<h1>Manage Users</h1>
<a href="../../admin.php">Back to Admin Panel</a>
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Role</th>
<th>Action</th>
</tr>
<?php while($u = $users->fetch_assoc()): ?>
<tr>
<td><?php echo $u['id']; ?></td>
<td><?php echo $u['name']; ?></td>
<td><?php echo $u['email']; ?></td>
<td><?php echo $u['phone']; ?></td>
<td><?php echo $u['role']; ?></td>
<td>
    <a href="delete_user.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
