<?php
session_start();
require 'db.php';

// Fetch available menu items
$masakans = $conn->query("SELECT * FROM masakan");

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Define user roles
$id_level = $_SESSION['id_level'];
$isAdmin = $id_level == 1;
$isUser = $id_level == 2;
$isOwner = $id_level == 3;
$isWaiter = $id_level == 4;
$isCashier = $id_level == 5;

// Handle Add to Cart for users
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart']) && $isUser) {
    $id_user = $_SESSION['id_user'];
    $id_masakan = $_POST['id_masakan'];
    $quantity = 1; // Default quantity

    // Check if the item is already in the cart
    $checkCart = $conn->prepare("SELECT * FROM cart WHERE id_user = ? AND id_masakan = ?");
    $checkCart->bind_param("ii", $id_user, $id_masakan);
    $checkCart->execute();
    $result = $checkCart->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if item already in cart
        $updateCart = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id_user = ? AND id_masakan = ?");
        $updateCart->bind_param("ii", $id_user, $id_masakan);
        $updateCart->execute();
    } else {
        // Insert new item into the cart
        $addCart = $conn->prepare("INSERT INTO cart (id_user, id_masakan, quantity) VALUES (?, ?, ?)");
        $addCart->bind_param("iii", $id_user, $id_masakan, $quantity);
        $addCart->execute();
    }

    // Add to orders (only if the item isn't already marked as not received)
    $checkOrder = $conn->prepare("SELECT * FROM orders WHERE id_user = ? AND id_masakan = ? AND status = 'not_received'");
    $checkOrder->bind_param("ii", $id_user, $id_masakan);
    $checkOrder->execute();
    $resultOrder = $checkOrder->get_result();

    if ($resultOrder->num_rows > 0) {
        // Update quantity in the orders table
        $updateOrder = $conn->prepare("UPDATE orders SET quantity = quantity + 1 WHERE id_user = ? AND id_masakan = ? AND status = 'not_received'");
        $updateOrder->bind_param("ii", $id_user, $id_masakan);
        $updateOrder->execute();
    } else {
        // Insert new item into the orders table
        $addOrder = $conn->prepare("INSERT INTO orders (id_user, id_masakan, quantity, status) VALUES (?, ?, ?, 'not_received')");
        $addOrder->bind_param("iii", $id_user, $id_masakan, $quantity);
        $addOrder->execute();
    }

    $success = "Item added to cart and order!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Restaurant Name</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<!-- Sidebar -->
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php" class="active">Home</a>
    <?php if ($isAdmin) : ?>
        <a href="workers.php">Manage Workers</a>
        <a href="masakan.php">Manage Menu</a>
        <a href="manage_orders.php">Manage Payments</a>
        <a href="manage_order.php">Manage Orders</a>
        <a href="view_reports.php">View Reports</a>
    <?php endif; ?>
    <?php if ($isUser) : ?>
        <a href="view_cart.php">View Cart</a>
        <a href="report.php">Submit Report</a> <!-- Users can submit reports -->
    <?php endif; ?>
    <?php if ($isOwner) : ?>
        <a href="reports.php">View Reports</a>
        <a href="manage_staff.php">Manage Staff</a>
    <?php endif; ?>
    <?php if ($isWaiter) : ?>
        <a href="manage_order.php">Manage Orders</a>
    <?php endif; ?>
    <?php if ($isCashier) : ?>
        <a href="process_payment.php">Process Payments</a>
    <?php endif; ?>
    <a href="logout.php" class="logout">Logout</a>
</div>


<!-- Main Content -->
<div class="content">
    <h1 class="mb-4">Current Menu</h1>
    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php while ($row = $masakans->fetch_assoc()) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($row['image'])) : ?>
                        <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['nama_masakan']; ?>">
                    <?php else : ?>
                        <img src="default-placeholder.png" class="card-img-top" alt="No Image">
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $row['nama_masakan']; ?></h5>
                        <p class="card-text">Price: Rp<?php echo number_format($row['harga'], 2); ?></p>
                        <?php if ($isUser) : ?>
                            <form method="POST">
                                <input type="hidden" name="id_masakan" value="<?php echo $row['id_masakan']; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Restaurant Name. All Rights Reserved.</p>
</footer>

</body>
</html>
