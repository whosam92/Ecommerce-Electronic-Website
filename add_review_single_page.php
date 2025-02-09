<?php
session_start();
include './adminDashboard/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to leave a review.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);
    $review_text = trim($_POST['review_text']);

    if ($rating < 1 || $rating > 5 || empty($review_text)) {
        die("Error: Invalid input.");
    }

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review_text);

    if ($stmt->execute()) {
        header("Location: single-product.php?id=$product_id");
        exit();
    } else {
        die("Error: Could not save your review.");
    }
}
?>
