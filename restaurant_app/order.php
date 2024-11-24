<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: login.php");
    exit();
}

// Fetch orders with correct column names from `customer_order` table
$orders = $conn->query("SELECT id_order, no_meja, tanggal, customer_name, total_amount, status_order FROM customer_order");

if (!$orders) {
    die("Error fetching orders: " . $conn->error); // Debugging error message
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Manage Orders</h1>
    <?php if ($orders->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Table Number</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id_order']); ?></td>
                        <td><?php echo htmlspecialchars($order['no_meja']); ?></td>
                        <td><?php echo htmlspecialchars($order['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
                        <td><?php echo $order['status_order'] ? 'Completed' : 'Pending'; ?></td>
                        <td>
                            <a href="edit_order.php?id=<?php echo $order['id_order']; ?>" class="btn btn-primary">Edit</a>
                            <a href="delete_order.php?id=<?php echo $order['id_order']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders available.</p>
    <?php endif; ?>
</div>
</body>
</html>
