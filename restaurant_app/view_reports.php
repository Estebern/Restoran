<?php
session_start();
require 'db.php';

// Check if the user is Admin
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 1) {
    header("Location: index.php");
    exit();
}

// Fetch all reports
$reports = $conn->query("SELECT r.id_report, u.nama_user, r.report_content, r.created_at FROM reports r JOIN user u ON r.id_user = u.id_user ORDER BY r.created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="sidebar">
    <h2><?php echo htmlspecialchars($_SESSION['nama_user']); ?>'s Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="view_reports.php" class="active">View Reports</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Reports</h1>
    <?php if ($reports && $reports->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>User</th>
                    <th>Report Content</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($report = $reports->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($report['id_report']); ?></td>
                        <td><?= htmlspecialchars($report['nama_user']); ?></td>
                        <td><?= htmlspecialchars($report['report_content']); ?></td>
                        <td><?= htmlspecialchars($report['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No reports available.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
