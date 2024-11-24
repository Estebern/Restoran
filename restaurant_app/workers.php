<?php
session_start();
require 'db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: login.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama_user = $_POST['nama_user'];
        $username = $_POST['username'];
        $password = $_POST['password']; // Plain text password for practice
        $id_level = $_POST['id_level'];

        $stmt = $conn->prepare("INSERT INTO user (nama_user, username, password, id_level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nama_user, $username, $password, $id_level);
        $message = $stmt->execute() ? "Worker added successfully!" : "Failed to add worker!";
    } elseif (isset($_POST['edit'])) {
        $id_user = $_POST['id_user'];
        $nama_user = $_POST['edit_nama_user'];
        $username = $_POST['edit_username'];
        $id_level = $_POST['edit_id_level'];

        $stmt = $conn->prepare("UPDATE user SET nama_user = ?, username = ?, id_level = ? WHERE id_user = ?");
        $stmt->bind_param("ssii", $nama_user, $username, $id_level, $id_user);
        $message = $stmt->execute() ? "Worker updated successfully!" : "Failed to update worker!";
    } elseif (isset($_POST['delete'])) {
        $id_user = $_POST['id_user'];
        $stmt = $conn->prepare("DELETE FROM user WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $message = $stmt->execute() ? "Worker deleted successfully!" : "Failed to delete worker!";
    }
}

// Fetch workers
$workers = $conn->query("SELECT u.id_user, u.nama_user, u.username, l.nama_level FROM user u JOIN level l ON u.id_level = l.id_level");
$levels = $conn->query("SELECT * FROM level WHERE id_level > 1"); // Exclude Admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="workers.php" class="active">Manage Workers</a>
    <a href="masakan.php">Manage Menu</a>
    <a href="manage_order.php">Manage Orders</a>
    <a href="logout.php">Logout</a>
</div>
<div class="content">
    <h1>Manage Workers</h1>
    <p><?php echo $message; ?></p>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label>Nama User</label>
            <input type="text" name="nama_user" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="id_level" class="form-control" required>
                <?php while ($level = $levels->fetch_assoc()) : ?>
                    <option value="<?php echo $level['id_level']; ?>"><?php echo $level['nama_level']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="add" class="btn btn-success">Add Worker</button>
    </form>

    <div class="row">
        <?php while ($worker = $workers->fetch_assoc()) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $worker['nama_user']; ?></h5>
                        <p class="card-text">Username: <?php echo $worker['username']; ?></p>
                        <p class="card-text">Role: <?php echo $worker['nama_level']; ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_user" value="<?php echo $worker['id_user']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                        <button type="button" class="btn btn-info" onclick="showEditForm('<?php echo $worker['id_user']; ?>', '<?php echo $worker['nama_user']; ?>', '<?php echo $worker['username']; ?>', '<?php echo $worker['id_level']; ?>')">Edit</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Worker</h3>
        <form method="POST">
            <input type="hidden" name="id_user" id="edit_id">
            <div class="mb-3">
                <label>Nama User</label>
                <input type="text" name="edit_nama_user" id="edit_nama_user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="edit_username" id="edit_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="edit_id_level" id="edit_id_level" class="form-control" required>
                    <?php $levels->data_seek(0); while ($level = $levels->fetch_assoc()) : ?>
                        <option value="<?php echo $level['id_level']; ?>"><?php echo $level['nama_level']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="edit" class="btn btn-primary">Update Worker</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
