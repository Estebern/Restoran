<?php
session_start();
require 'db.php';

// Check if user is logged in and is either Admin or Waiter
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['id_level'], [1, 4])) {
    header("Location: index.php");
    exit();
}

// Define user roles
$isAdmin = $_SESSION['id_level'] == 1;

// Fetch orders
$orders = $conn->query("
    SELECT o.id_order, u.nama_user, m.nama_masakan, o.quantity, o.status 
    FROM orders o 
    JOIN user u ON o.id_user = u.id_user 
    JOIN masakan m ON o.id_masakan = m.id_masakan
    WHERE o.status != 'completed'
");

// Handle toggling of order status
if (isset($_GET['toggle_status']) && isset($_GET['id_order'])) {
    $orderId = (int) $_GET['id_order'];
    $currentStatus = $_GET['toggle_status'];

    // Determine the new status
    $newStatus = ($currentStatus === 'received') ? 'not_received' : 'received';

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_order = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to manage orders
    header("Location: manage_order.php");
    exit();
}

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $id_order = (int) $_POST['id_order'];

    // Delete the order from the database
    $stmt = $conn->prepare("DELETE FROM orders WHERE id_order = ?");
    $stmt->bind_param("i", $id_order);
    $stmt->execute();
    $stmt->close();

    // Redirect back to manage orders
    header("Location: manage_order.php");
    exit();
}
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
    <?php if ($isAdmin): ?>
        <a href="workers.php">Manage Workers</a>
        <a href="masakan.php">Manage Menu</a>
        <a href="manage_orders.php">Manage Payments</a>
    <?php endif; ?>
    <a href="manage_order.php" class="active">Manage Orders</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Manage Orders</h1>
    <?php if ($orders && $orders->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id_order']); ?></td>
                        <td><?= htmlspecialchars($order['nama_user']); ?></td>
                        <td><?= htmlspecialchars($order['nama_masakan']); ?></td>
                        <td><?= $order['quantity']; ?></td>
                        <td>
                            <span class="badge bg-<?= $order['status'] === 'received' ? 'success' : 'danger'; ?>">
                                <?= ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="manage_order.php?toggle_status=<?= $order['status']; ?>&id_order=<?= $order['id_order']; ?>" 
                                   class="btn btn-sm <?= $order['status'] === 'received' ? 'btn-danger' : 'btn-success'; ?>">
                                    <?= $order['status'] === 'received' ? 'Mark as Not Received' : 'Mark as Received'; ?>
                                </a>
                                <?php if ($order['status'] === 'received'): ?>
                                    <form method="POST" action="manage_order.php" style="display: inline;">
                                        <input type="hidden" name="id_order" value="<?= $order['id_order']; ?>">
                                        <button type="submit" name="delete_order" class="btn btn-sm btn-secondary">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No orders to manage.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
