<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 2) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Fetch all items from the user's cart
$cartItems = $conn->query("
    SELECT c.id_masakan, c.quantity, m.harga
    FROM cart c
    JOIN masakan m ON c.id_masakan = m.id_masakan
    WHERE c.id_user = $id_user
");

if ($cartItems->num_rows > 0) {
    // Begin transaction to ensure atomicity
    $conn->begin_transaction();
    try {
        // Insert each cart item into the orders table
        while ($item = $cartItems->fetch_assoc()) {
            $id_masakan = $item['id_masakan'];
            $quantity = $item['quantity'];
            $harga = $item['harga'];
            $status = 'pending'; // Orders start as "pending"

            $stmt = $conn->prepare("
                INSERT INTO orders (id_user, id_masakan, quantity, harga, status)
                VALUES (?, ?, ?, ?, ?)
            ");

            if ($stmt === false) {
                throw new Exception('MySQL prepare error: ' . $conn->error);
            }

            $stmt->bind_param("iiiss", $id_user, $id_masakan, $quantity, $harga, $status);
            if (!$stmt->execute()) {
                throw new Exception('Execute error: ' . $stmt->error);
            }
        }

        // Clear the user's cart
        $deleteCart = $conn->query("DELETE FROM cart WHERE id_user = $id_user");
        if (!$deleteCart) {
            throw new Exception('Error clearing cart: ' . $conn->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to the order confirmation page
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        die("Checkout failed: " . $e->getMessage());
    }
} else {
    echo "<h2>Your cart is empty!</h2>";
    echo '<a href="view_cart.php" class="btn btn-secondary">Go back to Cart</a>';
    exit();
}
?>
