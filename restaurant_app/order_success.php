<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }

        .message-box {
            padding: 20px;
            border: 1px solid #28a745;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            display: inline-block;
        }

        a {
            text-decoration: none;
            color: #007bff;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Order Placed Successfully!</h1>
        <p>Your order has been sent to the waiter.</p>
        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
