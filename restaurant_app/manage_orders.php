<?php
session_start();
require 'db.php';

// Check if the user is logged in and is either admin or cashier
if (!isset($_SESSION['id_user']) || ($_SESSION['id_level'] != 1 && $_SESSION['id_level'] != 5)) {
    header("Location: index.php");
    exit();
}

// Fetch all pending orders
$orders = $conn->query("
    SELECT o.id_order, u.nama_user, m.nama_masakan, o.quantity, o.harga, o.status
    FROM orders o
    JOIN user u ON o.id_user = u.id_user
    JOIN masakan m ON o.id_masakan = m.id_masakan
    WHERE o.status = 'pending'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="workers.php">Manage Workers</a>
    <a href="masakan.php">Manage Menu</a>
    <a href="manage_orders.php">Manage Payment</a>
    <a href="manage_order.php">Manage Orders</a>
    <a href="report.php" class="active">Report</a> <!-- Link visible for all users -->
    <a href="logout.php">Logout</a>
</div>


<div class="content">
    <h1>Manage Orders</h1>
    <?php if ($orders->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <?php
                    // Calculate the total price for each order
                    $total_price = $order['quantity'] * $order['harga'];
                    ?>
                    <tr>
                        <td><?= $order['id_order'] ?></td>
                        <td><?= htmlspecialchars($order['nama_user']) ?></td>
                        <td><?= htmlspecialchars($order['nama_masakan']) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td>Rp<?= number_format($order['harga'], 2) ?></td>
                        <td>Rp<?= number_format($total_price, 2) ?></td>
                        <td><?= ucfirst($order['status']) ?></td>
                        <td>
                            <form action="process_order.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                                <button type="submit" name="action" value="complete" class="btn btn-success btn-sm">Complete</button>
                            </form>
                            <form action="process_order.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                                <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No pending orders found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
