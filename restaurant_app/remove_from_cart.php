<?php
session_start();
require 'db.php';

if (isset($_GET['id_masakan'])) {
    $id_masakan = $_GET['id_masakan'];
    $conn->query("DELETE FROM cart WHERE id_user = {$_SESSION['id_user']} AND id_masakan = $id_masakan");
}

header('Location: view_cart.php');
?>
