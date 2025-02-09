<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM products WHERE id = $id");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Delete image from folder
        if (file_exists($product['image'])) {
            unlink($product['image']);
        }

        // Delete product from DB
        $conn->query("DELETE FROM products WHERE id = $id");
    }
}
header("Location: view_products.php");
exit;
?>
