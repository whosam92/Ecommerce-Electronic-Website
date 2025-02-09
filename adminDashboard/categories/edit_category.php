<?php
include '../db.php';

$message = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM categories WHERE id = $id");

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>❌ Category not found.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($conn->real_escape_string($_POST['name']));
    $description = trim($conn->real_escape_string($_POST['description']));

    if (empty($name)) {
        $message = "<div class='alert alert-danger'>❌ Category name is required.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Category updated successfully!</div>";
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
    <title>Edit Category</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Edit Category</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="edit_category.php?id=<?php echo $id; ?>" class="p-4 border rounded shadow-sm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $category['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"><?php echo $category['description']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="view_categories.php" class="btn btn-secondary">Back to Categories</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
