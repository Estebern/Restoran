<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] === 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_masakan = $_POST['id_masakan'];
    $quantity = $_POST['quantity'];
    $id_user = $_SESSION['id_user'];

    // Validate quantity
    if ($quantity < 1) {
        die("Invalid quantity.");
    }

    // Insert the order into the database
    $orderQuery = $conn->prepare("INSERT INTO orders (id_user, id_masakan, quantity) VALUES (?, ?, ?)");
    $orderQuery->bind_param("iii", $id_user, $id_masakan, $quantity);

    if ($orderQuery->execute()) {
        echo "Order placed successfully!";
        header("Location: index.php");
    } else {
        echo "Error placing order: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}
