<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 2) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Fetch items in the user's cart
$cartItems = $conn->query("
    SELECT c.id_masakan, c.quantity, m.nama_masakan, m.harga
    FROM cart c
    JOIN masakan m ON c.id_masakan = m.id_masakan
    WHERE c.id_user = $id_user
");

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3">
            <h4>Menu</h4>
            <ul class="list-unstyled">
                <li><a href="homepage.php" class="text-decoration-none">Home</a></li>
                <li><a href="view_cart.php" class="text-decoration-none">View Cart</a></li>
                <li><a href="logout.php" class="text-decoration-none">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container my-4">
            <h2>Checkout</h2>
            <?php if ($cartItems->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $cartItems->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_masakan']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>Rp<?= number_format($item['harga'], 2) ?></td>
                                <td>Rp<?= number_format($item['harga'] * $item['quantity'], 2) ?></td>
                            </tr>
                            <?php $totalPrice += $item['harga'] * $item['quantity']; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <h4>Total: Rp<?= number_format($totalPrice, 2) ?></h4>

                <!-- Checkout Form -->
                <form action="process_checkout.php" method="POST">
                    <button type="submit" class="btn btn-success">Confirm Checkout</button>
                    <a href="view_cart.php" class="btn btn-secondary">Back to Cart</a>
                </form>
            <?php else: ?>
                <p>Your cart is empty. <a href="index.php" class="text-decoration-none">Go back to shopping</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
