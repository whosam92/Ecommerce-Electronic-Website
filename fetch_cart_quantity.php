<?php
// Start session
session_start();

// Include database connection
require 'adminDashboard/db.php'; // Adjust path to your connection file

// Initialize response
$response = ['success' => false, 'quantity' => 0];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch total quantity of items in the cart for the logged-in user
    $sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $response['success'] = true;
    $response['quantity'] = $row['total_quantity'] ?? 0; // Default to 0 if no items in the cart
    $stmt->close();
}

// Return JSON response
echo json_encode($response);
?>
