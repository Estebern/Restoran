<?php
include 'config.php';

$query = $pdo->query("
    SELECT orders.id AS order_id, users.username, orders.total_amount, orders.created_at 
    FROM orders 
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
");
$orders = $query->fetchAll();

foreach ($orders as $order) {
    echo "<h3>Order #{$order['order_id']} by {$order['username']} (Rp" . number_format($order['total_amount'], 2) . ")</h3>";
    $details_query = $pdo->prepare("
        SELECT menu.name, order_details.quantity, order_details.price 
        FROM order_details 
        JOIN menu ON order_details.item_id = menu.id 
        WHERE order_details.order_id = ?
    ");
    $details_query->execute([$order['order_id']]);
    $details = $details_query->fetchAll();

    foreach ($details as $detail) {
        echo "<div>{$detail['name']} x {$detail['quantity']} = Rp" . number_format($detail['price'] * $detail['quantity'], 2) . "</div>";
    }
}
?>
