<?php
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $discountCode = trim($_POST['discountCode']);
    $percentage = floatval($_POST['percentage']);
    $order_id = intval($_POST['order_id']);
    $created_at = date('Y-m-d H:i:s');

    // Insert discount into the database
    $stmt = $conn->prepare("INSERT INTO discounts (discountCode, percentage, order_id, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $discountCode, $percentage, $order_id, $created_at);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Discount added successfully!</div>";
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
    <title>Add Discount</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Add New Discount</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="add_discount.php" class="p-4 border rounded shadow-sm">
            <div class="mb-3">
                <label for="discountCode" class="form-label">Discount Code</label>
                <input type="text" name="discountCode" id="discountCode" class="form-control" placeholder="Enter Discount Code" required>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Percentage</label>
                <input type="number" step="0.01" name="percentage" id="percentage" class="form-control" placeholder="Enter Discount Percentage" required>
            </div>

            <div class="mb-3">
                <label for="order_id" class="form-label">Order ID</label>
                <input type="number" name="order_id" id="order_id" class="form-control" placeholder="Enter Order ID" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Discount</button>
            <a href="view_discounts.php" class="btn btn-secondary">Back to Discounts</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
