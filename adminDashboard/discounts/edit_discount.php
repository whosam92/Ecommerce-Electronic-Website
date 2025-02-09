<?php
include '../db.php';

$message = '';
$discount = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM discounts WHERE id = $id");

    if ($result->num_rows > 0) {
        $discount = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>❌ Discount not found!</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $discountCode = trim($_POST['discountCode']);
    $percentage = floatval($_POST['percentage']);
    $order_id = intval($_POST['order_id']);

    $stmt = $conn->prepare("UPDATE discounts SET discountCode = ?, percentage = ?, order_id = ? WHERE id = ?");
    $stmt->bind_param("sdii", $discountCode, $percentage, $order_id, $id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Discount updated successfully!</div>";
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
    <title>Edit Discount</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Edit Discount</h2>

        <?php if (!empty($message)) echo $message; ?>

        <?php if ($discount): ?>
        <form method="POST" action="edit_discount.php?id=<?php echo $id; ?>" class="p-4 border rounded shadow-sm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label for="discountCode" class="form-label">Discount Code</label>
                <input type="text" name="discountCode" id="discountCode" class="form-control" value="<?php echo $discount['discountCode']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Percentage</label>
                <input type="number" step="0.01" name="percentage" id="percentage" class="form-control" value="<?php echo $discount['percentage']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="order_id" class="form-label">Order ID</label>
                <input type="number" name="order_id" id="order_id" class="form-control" value="<?php echo $discount['order_id']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Discount</button>
            <a href="view_discounts.php" class="btn btn-secondary">Back to Discounts</a>
        </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
