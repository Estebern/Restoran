<?php
session_start();
require 'db.php';

// Check if user is logged in and is Admin (level 1)
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$editWorker = null;
$message = '';

// Fetch levels for the dropdown
$levels = $conn->query("SELECT * FROM level")->fetch_all(MYSQLI_ASSOC);

// Fetch all workers
$result = $conn->query("
    SELECT u.id_user, u.username, u.nama_user, l.nama_level, u.id_level
    FROM user u 
    LEFT JOIN level l ON u.id_level = l.id_level
");
$workers = $result->fetch_all(MYSQLI_ASSOC);

// Handle add/edit worker form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? null;
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama_user = trim($_POST['nama_user']);
    $id_level = $_POST['id_level'];

    if ($id_user) {
        // Update existing worker
        $sql = "UPDATE user SET username = ?, nama_user = ?, id_level = ?";
        $params = [$username, $nama_user, $id_level];

        // Update password if provided
        if (!empty($password)) {
            $sql .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id_user = ?";
        $params[] = $id_user;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
        $stmt->close();
        $message = "Worker updated successfully!";
    } else {
        // Add new worker
        $stmt = $conn->prepare("
            INSERT INTO user (username, password, nama_user, id_level) 
            VALUES (?, ?, ?, ?)
        ");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssi", $username, $hashedPassword, $nama_user, $id_level);
        $stmt->execute();
        $stmt->close();
        $message = "Worker added successfully!";
    }

    header("Location: workers.php");
    exit();
}

// Handle edit and delete actions
if (isset($_GET['edit'])) {
    $id_user = (int) $_GET['edit'];
    $result = $conn->query("SELECT * FROM user WHERE id_user = $id_user");
    $editWorker = $result->fetch_assoc();
} elseif (isset($_GET['delete'])) {
    $id_user = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM user WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $stmt->close();
    $message = "Worker deleted successfully!";
    header("Location: workers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }
        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            margin: 5px 0;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            border-radius: 4px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="index.php">Home</a>
        <a href="workers.php" class="active">Manage Workers</a>
        <a href="masakan.php">Manage Menu</a>
        <a href="manage_order.php">Manage Orders</a>
        <a href="manage_orders.php">Manage Payment</a>
        <a href="reports.php">View Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Manage Workers</h1>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- Table for displaying workers -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workers as $worker): ?>
                    <tr>
                        <td><?= $worker['id_user'] ?></td>
                        <td><?= htmlspecialchars($worker['username']) ?></td>
                        <td><?= htmlspecialchars($worker['nama_user']) ?></td>
                        <td><?= htmlspecialchars($worker['nama_level']) ?></td>
                        <td>
                            <a href="?edit=<?= $worker['id_user'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="?delete=<?= $worker['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Form for adding/updating workers -->
        <form method="post">
            <h2><?= $editWorker ? 'Edit' : 'Add' ?> Worker</h2>
            <input type="hidden" name="id_user" value="<?= $editWorker['id_user'] ?? '' ?>">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" value="<?= $editWorker['username'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" <?= $editWorker ? '' : 'required' ?>>
            </div>
            <div class="mb-3">
                <input type="text" name="nama_user" class="form-control" placeholder="Name" value="<?= $editWorker['nama_user'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <select name="id_level" class="form-control" required>
                    <option value="">Select Level</option>
                    <?php foreach ($levels as $level): ?>
                        <option value="<?= $level['id_level'] ?>" <?= isset($editWorker['id_level']) && $editWorker['id_level'] == $level['id_level'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($level['nama_level']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success"><?= $editWorker ? 'Update' : 'Add' ?> Worker</button>
        </form>
    </div>
</body>
</html>
