<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location:../orders/view_order.php?message=Order deleted successfully");
        exit;
    } else {
        echo "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>
