<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate `order_id`
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = intval($_GET['order_id']);

try {
    // Verify that the order belongs to the user
    $check_order = $conn->prepare("SELECT id FROM orders WHERE id = :order_id AND user_id = :user_id");
    $check_order->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $check_order->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_order->execute();

    if ($check_order->rowCount() == 0) {
        die("Unauthorized request.");
    }

    // Retrieve order details
    $sql = "SELECT oi.quantity, oi.price, p.name 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Order Details #<?= htmlspecialchars($order_id); ?></h2>

        <?php if (count($items) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                            <td><?= htmlspecialchars($item['price']); ?> $</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No products found in this order.</div>
        <?php endif; ?>

        <a href="my-account.php" class="btn btn-secondary">Back to Order History</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
