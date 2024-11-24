<?php
session_start();
require 'db.php';

// Enable error reporting for MySQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute the query
    $query = $conn->prepare("
        SELECT id_user AS id, username, password, nama_user AS name, id_level AS role 
        FROM user WHERE username = ? 
        UNION 
        SELECT id_worker AS id, username, password, name, role 
        FROM workers WHERE username = ?
    ");
    $query->bind_param("ss", $username, $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) { // Plain text comparison
            // Set session variables
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['nama_user'] = $user['name'];
            $_SESSION['id_level'] = $user['role'];

            // Redirect to the appropriate dashboard
            switch ($user['role']) {
                case 1: // Admin
                    header("Location: admin_dashboard.php");
                    break;
                case 2: // User
                    header("Location: user_dashboard.php");
                    break;
                case 3: // Owner
                    header("Location: owner_dashboard.php");
                    break;
                case 4: // Waiter
                    header("Location: waiter_dashboard.php");
                    break;
                case 5: // Cashier
                    header("Location: cashier_dashboard.php");
                    break;
                default:
                    header("Location: index.php");
                    break;
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6e7e8b, #9a8e8d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
        }
        h3 {
            text-align: center;
            color: #333;
        }
        button {
            background-color: #6e7e8b;
            color: white;
        }
        button:hover {
            background-color: #4a5a6b;
        }
        .alert {
            margin-bottom: 20px;
        }
        .text-center a {
            text-decoration: none;
            color: #6e7e8b;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h3 class="text-center">Restaurant Management Login</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
