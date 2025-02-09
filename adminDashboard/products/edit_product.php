<?php
include '../db.php';

$message = '';

// Fetch product details if id is provided in the URL
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>Product not found.</div>";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $stock = $conn->real_escape_string($_POST['stock']);
    $category_id = $conn->real_escape_string($_POST['category_id']);
    $image = $_FILES['image'];

    $errors = [];
    $imagePath = $product['image']; // Default to the current image if no new image is uploaded

    // Validate inputs
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $name)) {
        $errors[] = "Product name must contain only letters, numbers, and spaces.";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive number.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stock must be a non-negative number.";
    }

    // Handle new image upload if provided
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Define the absolute upload directory
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Ecommerce-Electronic-Website/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
        }

        // Generate a unique filename to avoid conflicts
        $uniqueName = time() . '_' . basename($image['name']);
        $imagePath = 'uploads/' . $uniqueName; // Relative path stored in the database
        $absoluteImagePath = $uploadDir . $uniqueName;

        // Validate file type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($image['tmp_name']);
        if (!in_array($fileType, $allowedMimeTypes)) {
            $errors[] = "Only JPG, PNG, and GIF files are allowed.";
        }

        // Move the uploaded file
        if (!move_uploaded_file($image['tmp_name'], $absoluteImagePath)) {
            $errors[] = "Failed to upload the image.";
        }
    }

    // Update the database if there are no errors
    if (empty($errors)) {
        $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                price = '$price', 
                stock = '$stock', 
                category_id = '$category_id', 
                image = '$imagePath'
                WHERE id = $id";

        if ($conn->query($sql)) {
            $message = "<div class='alert alert-success'>Product updated successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>" . implode('<br>', $errors) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Edit Product</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="edit_product.php?id=<?php echo $id; ?>" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <?php
                    $categories = $conn->query("SELECT * FROM categories");
                    while ($category = $categories->fetch_assoc()) {
                        $selected = $category['id'] == $product['category_id'] ? 'selected' : '';
                        echo "<option value='" . $category['id'] . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <?php if (!empty($product['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Ecommerce-Electronic-Website/' . $product['image'])): ?>
                    <img src="/Ecommerce-Electronic-Website/<?php echo $product['image']; ?>?t=<?php echo time(); ?>" alt="Product Image" class="img-thumbnail mt-2" style="width: 100px;">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="view_products.php" class="btn btn-secondary">Back to Products</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>