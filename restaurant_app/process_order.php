<?php
session_start();
require 'db.php';

// Check if the user is logged in and is either admin or cashier
if (!isset($_SESSION['id_user']) || ($_SESSION['id_level'] != 1 && $_SESSION['id_level'] != 5)) {
    header("Location: index.php");
    exit();
}

// Check if action is set and id_order is provided
if (isset($_POST['action']) && isset($_POST['id_order'])) {
    $id_order = $_POST['id_order'];
    $action = $_POST['action'];

    if ($action == 'complete') {
        // Update the order status to 'completed' (you can change the status name based on your requirements)
        $query = "UPDATE orders SET status = 'completed' WHERE id_order = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_order);
        $stmt->execute();

        // Optionally, delete the order if you don't want to keep completed orders
        // $deleteQuery = "DELETE FROM orders WHERE id_order = ?";
        // $deleteStmt = $conn->prepare($deleteQuery);
        // $deleteStmt->bind_param('i', $id_order);
        // $deleteStmt->execute();
    } elseif ($action == 'cancel') {
        // Update the order status to 'canceled'
        $query = "UPDATE orders SET status = 'canceled' WHERE id_order = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_order);
        $stmt->execute();

        // Optionally, delete the order if you want to remove canceled orders entirely
        // $deleteQuery = "DELETE FROM orders WHERE id_order = ?";
        // $deleteStmt = $conn->prepare($deleteQuery);
        // $deleteStmt->bind_param('i', $id_order);
        // $deleteStmt->execute();
    }

    // Redirect back to the manage orders page
    header("Location: manage_orders.php");
    exit();
}
?>
