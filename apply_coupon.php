<?php
session_start();
require 'adminDashboard/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$coupon = isset($_POST['coupon']) ? trim($_POST['coupon']) : '';
$user_id = $_SESSION['user_id'];

if ($coupon) {
    // Validate the coupon
    $stmt = $conn->prepare("SELECT percentage FROM discounts WHERE discountCode = ?");
    $stmt->bind_param('s', $coupon);
    $stmt->execute();
    $stmt->bind_result($discount_percentage);
    $stmt->fetch();
    $stmt->close();

    if ($discount_percentage) {
        // Calculate the total price
        $stmt = $conn->prepare("SELECT SUM(p.price * c.quantity) AS total_price 
                                FROM cart c 
                                INNER JOIN products p ON c.product_id = p.id 
                                WHERE c.user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($total_price);
        $stmt->fetch();
        $stmt->close();

        // Apply the discount
        $discounted_price = $total_price - ($total_price * ($discount_percentage / 100));

        echo json_encode(['success' => true, 'discounted_price' => $discounted_price]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid coupon code']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Coupon code cannot be empty']);
}
?>
