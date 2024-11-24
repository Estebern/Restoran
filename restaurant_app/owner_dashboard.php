<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 3) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['nama_user']); ?>!</h1>
        <p>This is your dashboard. Currently, only the home page is available.</p>
        <a href="index.php" class="btn btn-primary">Go to Home</a>
    </div>
</body>
</html>
