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
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0fffb;
    margin: 0;
    padding: 20px;
    color: #222;
}
h1 {
    text-align: center;
    color: #00695c;
    margin-bottom: 20px;
}
a.button {
    display: inline-block;
    background: #004d40;
    color: #fff;
    padding: 8px 16px;
    margin: 5px 2px;
    text-decoration: none;
    border-radius: 6px;
}
a.button:hover {
    background: #00796b;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: #e0f2f1;
    color: #004d40;
}
tr:nth-child(even) {
    background: #f9f9f9;
}
a.delete-link {
    color: #e53935;
    text-decoration: none;
    font-weight: 600;
    background: #ffebee;
    padding: 6px 10px;
    border-radius: 6px;
}
a.delete-link:hover {
    background: #ffcdd2;
}
</style>
</head>
<body>

<h1>Manage Users</h1>
<a class="button" href="../../admin.php">Back to Admin Panel</a>

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
<td><?php echo htmlspecialchars($u['name']); ?></td>
<td><?php echo htmlspecialchars($u['email']); ?></td>
<td><?php echo htmlspecialchars($u['phone']); ?></td>
<td><?php echo htmlspecialchars($u['role']); ?></td>
<td>
    <a class="delete-link" href="delete_user.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>

