<?php
// Start session
session_start();

// Include database connection
require 'adminDashboard/db.php'; // Adjust path to your connection file

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in to manage your cart.");
}

// Get the product ID from the query string
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

if ($product_id) {
    // SQL query to delete the cart item for the specific user and product
    $sql = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $product_id, $user_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect back to the cart page with a success message
        header("Location: cart.php?message=Item removed successfully");
    } else {
        // Redirect back with an error message
        header("Location: cart.php?message=Item not found in your cart");
    }
    $stmt->close();
} else {
    // If product_id is missing, return an error
    die("Invalid product ID.");
}
?>
