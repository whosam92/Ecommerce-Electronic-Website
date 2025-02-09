<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM discounts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_discounts.php?message=Discount deleted successfully!");
    } else {
        header("Location: view_discounts.php?error=Error deleting discount: " . $conn->error);
    }

    $stmt->close();
} else {
    header("Location: view_discounts.php");
}
