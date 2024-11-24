<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="index.php">Home</a>
        <a href="workers.php">Manage Workers</a>
        <a href="masakan.php">Manage Menu</a>
        <a href="manage_order.php">Manage Orders</a>
        <a href="manage_orders.php">Manage Payments</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['nama_user']); ?>!</h1>
        <p>Use the sidebar to navigate.</p>
    </div>
</body>
</html>
