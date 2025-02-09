<?php
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($conn->real_escape_string($_POST['name']));
    $description = trim($conn->real_escape_string($_POST['description']));
    $created_at = date('Y-m-d H:i:s');

    // Validation
    if (empty($name)) {
        $message = "<div class='alert alert-danger'>❌ Category name is required.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name, description, created_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $description, $created_at);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Category added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Add New Category</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="add_category.php" class="p-4 border rounded shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Enter description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Category</button>
            <a href="view_categories.php" class="btn btn-secondary">Back to Categories</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
