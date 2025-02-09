<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_categories.php?success=Category deleted successfully!");
    } else {
        header("Location: view_categories.php?error=Failed to delete category.");
    }
    $stmt->close();
} else {
    header("Location: view_categories.php?error=Invalid request.");
}
?>
