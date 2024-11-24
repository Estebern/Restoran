<?php
session_start();
require 'db.php';

// Check if user is logged in and is a normal user
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 2) {
    header("Location: index.php");
    exit();
}

// Fetch cart items for the user
$id_user = $_SESSION['id_user'];
$cartItems = $conn->query("SELECT c.id_masakan, m.nama_masakan, m.harga, c.quantity 
                           FROM cart c 
                           JOIN masakan m ON c.id_masakan = m.id_masakan 
                           WHERE c.id_user = $id_user");

// Calculate total cart price
$totalPrice = 0;
if ($cartItems->num_rows > 0) {
    while ($item = $cartItems->fetch_assoc()) {
        $totalPrice += $item['harga'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #212529;
            color: #fff;
            padding: 20px;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            padding: 12px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #343a40;
            color: white;
        }

        .cart-summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .cart-summary h3 {
            font-size: 18px;
        }

        .checkout-button, .empty-cart-button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .checkout-button:hover, .empty-cart-button:hover {
            background-color: #0056b3;
        }

        .empty-cart {
            margin-top: 20px;
        }

        .empty-cart a {
            background-color: #dc3545;
        }

        .empty-cart a:hover {
            background-color: #c82333;
        }

        .form-container {
            display: flex;
            align-items: center;
        }

        .form-container input[type="number"] {
            padding: 5px;
            width: 50px;
            margin-right: 10px;
        }

        .form-container button {
            padding: 5px 10px;
            background-color: #28a745;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-container button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="sidebar">
<h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="view_cart.php" class="active">View Cart</a>
    <a href="report.php" class="active">Report</a> <!-- Link visible for all users -->
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Your Cart</h1>
    <?php if ($cartItems->num_rows > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the pointer for the cart items to loop again
                $cartItems->data_seek(0);
                while ($item = $cartItems->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $item['nama_masakan']; ?></td>
                        <td>Rp<?php echo number_format($item['harga'], 2); ?></td>
                        <td>
                            <form method="POST" action="update_cart.php" class="form-container">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <input type="hidden" name="id_masakan" value="<?php echo $item['id_masakan']; ?>">
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                        </td>
                        <td>Rp<?php echo number_format($item['harga'] * $item['quantity'], 2); ?></td>
                        <td>
                            <a href="remove_from_cart.php?id_masakan=<?php echo $item['id_masakan']; ?>">Remove</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Total Price -->
        <div class="cart-summary">
            <h3>Total: Rp<?php echo number_format($totalPrice, 2); ?></h3>
            <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
        </div>

        <!-- Empty Cart Button -->
        <div class="empty-cart">
            <a href="empty_cart.php" class="empty-cart-button">Empty Cart</a>
        </div>
    <?php else : ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>
</div>

</body>
</html>
