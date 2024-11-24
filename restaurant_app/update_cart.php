<?php
session_start();
require 'db.php';

if (isset($_POST['update_quantity'])) {
    $id_masakan = $_POST['id_masakan'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        $conn->query("UPDATE cart SET quantity = $quantity WHERE id_user = {$_SESSION['id_user']} AND id_masakan = $id_masakan");
    }

    header('Location: view_cart.php');
}
?>
