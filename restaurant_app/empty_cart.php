<?php
session_start();
require 'db.php';

$conn->query("DELETE FROM cart WHERE id_user = {$_SESSION['id_user']}");

header('Location: view_cart.php');
?>
