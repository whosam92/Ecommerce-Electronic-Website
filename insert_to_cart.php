<?php
session_start();
require 'adminDashboard/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
$user_id = $_SESSION['user_id'];

if ($product_id) {
    $sql = "UPDATE cart SET quantity = quantity + 1, updated_at = NOW() WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $product_id, $user_id);
    if ($stmt->execute()) {
        // Fetch the updated quantity
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $product_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($new_quantity);
        $stmt->fetch();
        echo json_encode(['success' => true, 'new_quantity' => $new_quantity]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to increase quantity']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
}
?>
