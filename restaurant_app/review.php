<?php
session_start();
require 'db.php';

// Check if the user is logged in and is a user (level 2)
if (!isset($_SESSION['id_user']) || $_SESSION['id_level'] != 2) {
    header("Location: index.php");
    exit();
}

// Fetch all reviews
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review_content'])) {
    $review_content = $_POST['review_content'];
    $id_user = $_SESSION['id_user'];

    // Insert the review into the database
    $query = "INSERT INTO reviews (id_user, review_content, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $id_user, $review_content);
    $stmt->execute();

    // Redirect to refresh the page after submission
    header("Location: review.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
    <h1>Submit a Review</h1>

    <!-- Review Submission Form (Only for Users with level 2) -->
    <h2>Submit Your Review</h2>
    <form action="review.php" method="POST">
        <textarea name="review_content" rows="5" class="form-control" placeholder="Enter your review" required></textarea>
        <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
    </form>

    <hr>

    <h2>All Reviews</h2>
    <?php if ($reviews->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Submitted By</th>
                    <th>Content</th>
                    <th>Submission Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <tr>
                        <td><?= $review['id_review'] ?></td>
                        <td><?= htmlspecialchars($review['id_user']) ?></td>
                        <td><?= htmlspecialchars($review['review_content']) ?></td>
                        <td><?= $review['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No reviews found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
