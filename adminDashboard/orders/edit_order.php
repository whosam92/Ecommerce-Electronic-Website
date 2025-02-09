<?php
include '../db.php';

$message = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM orders WHERE id = $id");

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>❌ Order not found!</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $user_id = intval($_POST['user_id']);
    $total_price = floatval($_POST['total_price']);
    $updated_at = date('Y-m-d H:i:s');

    // Update order in the database
    $stmt = $conn->prepare("UPDATE orders SET user_id = ?, total_price = ?, updated_at = ? WHERE id = ?");
    $stmt->bind_param("idsi", $user_id, $total_price, $updated_at, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Order updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Edit Order</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="edit_order.php?id=<?php echo $id; ?>" class="p-4 border rounded shadow-sm">
            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">

            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="number" name="user_id" id="user_id" class="form-control" value="<?php echo $order['user_id']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="total_price" class="form-label">Total Price</label>
                <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" value="<?php echo $order['total_price']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Order</button>
            <a href="../orders/view_order.php" class="btn btn-secondary">Back to Orders</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
