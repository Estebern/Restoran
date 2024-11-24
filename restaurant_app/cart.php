<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Fetch cart items
$cartItems = $conn->prepare("
    SELECT c.id, m.nama_masakan, c.quantity, m.harga, (c.quantity * m.harga) AS total_price
    FROM cart c
    JOIN masakan m ON c.id_masakan = m.id_masakan
    WHERE c.id_user = ?
");
$cartItems->bind_param("i", $id_user);
$cartItems->execute();
$items = $cartItems->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Your Cart</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $items->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['nama_masakan']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>Rp<?php echo number_format($row['harga'], 2); ?></td>
                    <td>Rp<?php echo number_format($row['total_price'], 2); ?></td>
                    <td>
                        <form method="POST" action="remove_from_cart.php">
                            <input type="hidden" name="id_cart" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
