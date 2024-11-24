<?php
session_start();
require 'db.php';

// Check if the user is logged in and if the user is an admin
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: login.php");
    exit();
}

$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama_masakan = htmlspecialchars($_POST['nama_masakan']);
        $harga = $_POST['harga'];
        $status_masakan = isset($_POST['status_masakan']) ? 1 : 0;

        // Handle image upload securely
        if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            if (in_array($fileType, $allowedTypes)) {
                $targetDir = "uploads/";
                $uniqueName = uniqid() . "_" . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $stmt = $conn->prepare("INSERT INTO masakan (nama_masakan, harga, status_masakan, image) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sdss", $nama_masakan, $harga, $status_masakan, $targetFile);

                    if ($stmt->execute()) {
                        $message = "Menu item added successfully!";
                    } else {
                        $message = "Failed to add menu item!";
                    }
                } else {
                    $message = "Failed to upload image!";
                }
            } else {
                $message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            }
        } else {
            $message = "Image upload error.";
        }
    } elseif (isset($_POST['toggle_status'])) {
        $id_masakan = $_POST['id_masakan'];
        $current_status = $_POST['current_status'];
        $new_status = $current_status == 1 ? 0 : 1;

        $stmt = $conn->prepare("UPDATE masakan SET status_masakan = ? WHERE id_masakan = ?");
        $stmt->bind_param("ii", $new_status, $id_masakan);

        if ($stmt->execute()) {
            $message = "Menu item status updated!";
        } else {
            $message = "Failed to update status.";
        }
    } elseif (isset($_POST['delete'])) {
        $id_masakan = $_POST['id_masakan'];
        $stmt = $conn->prepare("SELECT image FROM masakan WHERE id_masakan = ?");
        $stmt->bind_param("i", $id_masakan);
        $stmt->execute();
        $stmt->bind_result($imagePath);
        $stmt->fetch();
        $stmt->close();

        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath); // Delete the file from the server
        }

        $stmt = $conn->prepare("DELETE FROM masakan WHERE id_masakan = ?");
        $stmt->bind_param("i", $id_masakan);

        if ($stmt->execute()) {
            $message = "Menu item deleted!";
        } else {
            $message = "Failed to delete menu item.";
        }
    }
}

$masakans = $conn->query("SELECT * FROM masakan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this menu item?");  
        }
    </script>
</head>
<body>
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="workers.php">Manage Workers</a>
    <a href="masakan.php">Manage Menu</a>
    <a href="manage_orders.php">Manage Payment</a>
    <a href="manage_order.php">Manage Orders</a>
    <a href="report.php" class="active">Report</a> <!-- Link visible for all users -->
    <a href="logout.php">Logout</a>
</div>


<div class="content">
    <h1>Menu Management</h1>
    <?php if ($message) : ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="nama_masakan">Nama Masakan</label>
            <input type="text" name="nama_masakan" id="nama_masakan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="harga">Harga</label>
            <input type="number" step="0.01" name="harga" id="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status_masakan">Status Masakan</label>
            <input type="checkbox" name="status_masakan" id="status_masakan" value="1"> Available
        </div>
        <div class="mb-3">
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button type="submit" name="add" class="btn btn-success">Add Menu Item</button>
    </form>

    <div class="row">
    <?php while ($row = $masakans->fetch_assoc()) : ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['nama_masakan']); ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['nama_masakan']); ?></h5>
                    <p class="card-text">Price: Rp<?php echo number_format($row['harga'], 2); ?></p>
                    <p class="card-text">Status: <?php echo $row['status_masakan'] ? 'Available' : 'Unavailable'; ?></p>
                    <div class="d-flex">
                        <!-- Delete Button -->
                        <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="id_masakan" value="<?php echo $row['id_masakan']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger me-2">Delete</button>
                        </form>
                        <!-- Toggle Availability Button -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_masakan" value="<?php echo $row['id_masakan']; ?>">
                            <input type="hidden" name="current_status" value="<?php echo $row['status_masakan']; ?>">
                            <button type="submit" name="toggle_status" class="btn btn-<?php echo $row['status_masakan'] ? 'warning' : 'success'; ?>">
                                <?php echo $row['status_masakan'] ? 'Set Unavailable' : 'Set Available'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

