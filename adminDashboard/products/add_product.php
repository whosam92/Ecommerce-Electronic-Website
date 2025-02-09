<?php
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $stock = $conn->real_escape_string($_POST['stock']);
    $category_id = $conn->real_escape_string($_POST['category_id']);
    $image = $_FILES['image'];

    $errors = [];

    // Validation
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        $errors[] = "Product name must contain only letters, numbers, and spaces.";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive number.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stock must be a non-negative number.";
    }

    $imagePath = '';
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Set absolute upload directory (Windows path)
        $uploadDir = 'C:/xampp/htdocs/Ecommerce-Electronic-Website/uploads/';
        
        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    
        // Generate a unique filename
        $uniqueName = basename($image['name']);
        $imagePath = 'uploads/' . $uniqueName; // This is what gets stored in the database
        $absoluteImagePath = $uploadDir . $uniqueName; // Full path for saving the file
    
        // Move uploaded file
        if (!move_uploaded_file($image['tmp_name'], $absoluteImagePath)) {
            $errors[] = "Error uploading image.";
        }
    } else {
        $errors[] = "No image uploaded or an error occurred.";
    }
    

    // Insert into the database if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO products (name, description, price, stock, category_id, image, created_at) 
                VALUES ('$name', '$description', '$price', '$stock', '$category_id', '$imagePath', NOW())";

        if ($conn->query($sql)) {
            $message = "<div class='alert alert-success'>Product added successfully!</div>";
        } else {
            // Debug SQL errors
            $message = "<div class='alert alert-danger'>Database Error: " . $conn->error . "</div>";
            echo "SQL Query: " . $sql; // Debugging SQL query
        }
    } else {
        // Output validation errors
        $message = "<div class='alert alert-danger'>" . implode('<br>', $errors) . "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Add New Product</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="add_product.php" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php
                    // Fetch categories from the database
                    $categories = $conn->query("SELECT id, name FROM categories");
                    if ($categories->num_rows > 0) {
                        while ($category = $categories->fetch_assoc()) {
                            echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No categories available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="view_products.php" class="btn btn-secondary">Back to Products</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>