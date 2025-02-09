<?php
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $total_price = floatval($_POST['total_price']);
    $created_at = date('Y-m-d H:i:s');

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total_price, $created_at);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Order added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Fetch users for the dropdown
$users = $conn->query("SELECT id, name FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Add New Order</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="add_order.php" class="p-4 border rounded shadow-sm">
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="" disabled selected>Select User</option>
                    <?php
                    if ($users->num_rows > 0) {
                        while ($user = $users->fetch_assoc()) {
                            echo "<option value='{$user['id']}'>{$user['name']}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No users available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="total_price" class="form-label">Total Price</label>
                <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" placeholder="Enter Total Price" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Order</button>
            <a href="../orders/view_order.php" class="btn btn-secondary">Back to Orders</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
