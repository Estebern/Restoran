<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the username already exists
    $checkQuery = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $checkQuery->bind_param("s", $username);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already taken!";
    } else {
        // Insert new user into the database with level 2 (Regular User) without password hashing
        $id_level = 2; // Regular user level
        $registerQuery = $conn->prepare("INSERT INTO user (nama_user, username, password, id_level) VALUES (?, ?, ?, ?)");
        $registerQuery->bind_param("sssi", $customer_name, $username, $password, $id_level);

        if ($registerQuery->execute()) {
            $success = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: Could not register. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background styling */
        body {
            background: linear-gradient(135deg, #6e7e8b, #9a8e8d); /* Gradient background */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Form container styling */
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px; /* Increased max-width for larger form */
            width: 100%;
            height: auto;
            box-sizing: border-box;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        /* Button styling */
        button {
            background-color: #6e7e8b;
            color: white;
        }

        button:hover {
            background-color: #4a5a6b;
        }

        /* Form control input fields */
        .form-control {
            margin-bottom: 15px;
            padding: 12px; /* Increase padding inside input fields */
        }

        /* Link to login */
        .text-center a {
            text-decoration: none;
            color: #6e7e8b;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        /* Error & Success message styles */
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="POST" action="">
        <h3 class="text-center">Register New User</h3>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (isset($success)) : ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
