<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 4) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Waiter Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Display the name of the waiter -->
        <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
        <a href="index.php">Home</a>
        <a href="manage_order.php">Manage Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['nama_user']); ?>!</h1>
        <p>This is your dashboard. You can manage the orders here.</p>
        <a href="manage_order.php" class="btn btn-primary">Go to Manage Orders</a>
    </div>
</body>
</html>
