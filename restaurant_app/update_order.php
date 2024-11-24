<?php
session_start();

// Check if user is logged in and is a waiter
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 4) {
    header("Location: index.php");
    exit();
}

// Connect to the database
require 'db.php';

// Get the order ID and status from the URL
$order_id = $_GET['id'];
$status = $_GET['status'];

// Update the order status to "received"
$query = "UPDATE orders SET status = ? WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    header("Location: manage_order.php"); // Redirect to manage orders page after successful update
    exit();
} else {
    echo "Error updating order status.";
}
?>
