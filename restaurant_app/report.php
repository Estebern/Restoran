<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

// Define user roles
$role = '';
if ($_SESSION['id_level'] == 1) {
    $role = 'Admin';
} elseif ($_SESSION['id_level'] == 2) {
    $role = 'User';
} elseif ($_SESSION['id_level'] == 3) {
    $role = 'Cashier';
} elseif ($_SESSION['id_level'] == 4) {
    $role = 'Waiter';
}

// Handle report submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_content'])) {
    $reportContent = $_POST['report_content'];

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO reports (id_user, report_content, role) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iss", $_SESSION['id_user'], $reportContent, $role);
        $stmt->execute();
        $stmt->close();
        echo "Report submitted successfully!";
    } else {
        echo "Failed to submit report.";
    }
}

// Fetch all reports (Admin view)
if ($_SESSION['id_level'] == 1) {
    $reports = $conn->query("SELECT r.id_report, u.nama_user, r.report_content, r.role, r.created_at 
                             FROM reports r
                             JOIN user u ON r.id_user = u.id_user");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <?php if ($_SESSION['id_level'] == 1): ?>
        <a href="workers.php">Manage Workers</a>
        <a href="masakan.php">Manage Menu</a>
        <a href="manage_orders.php">Manage Payments</a>
        <a href="reports.php" class="active">View Reports</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Submit a Report</h1>
    <form method="POST" action="report.php">
        <div class="form-group">
            <label for="report_content">Report Content</label>
            <textarea class="form-control" id="report_content" name="report_content" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit Report</button>
    </form>

    <h2 class="mt-5">Reports</h2>
    <?php if (isset($reports) && $reports->num_rows > 0): ?>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Content</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($report = $reports->fetch_assoc()): ?>
                    <tr>
                        <td><?= $report['id_report'] ?></td>
                        <td><?= htmlspecialchars($report['nama_user']) ?></td>
                        <td><?= htmlspecialchars($report['role']) ?></td>
                        <td><?= htmlspecialchars($report['report_content']) ?></td>
                        <td><?= $report['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No reports found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
