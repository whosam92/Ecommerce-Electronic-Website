<?php
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $name = trim($conn->real_escape_string($_POST['name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $password = trim($conn->real_escape_string($_POST['password']));
    $phone = trim($conn->real_escape_string($_POST['phone']));
    $country = trim($conn->real_escape_string($_POST['country']));
    $address = trim($conn->real_escape_string($_POST['address']));
    $role_id = intval($_POST['role_id']);
    $image = $_FILES['image'];

    // Validation Rules
    $errors = [];
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "❌ Name must contain only letters and spaces.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "❌ Invalid email format.";
    }
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "❌ Phone number must be exactly 10 digits.";
    }
    if (strlen($password) < 8) {
        $errors[] = "❌ Password must be at least 8 characters.";
    }

    // Validate Role ID
    $roleCheck = $conn->query("SELECT id FROM roles WHERE id = $role_id");
    if ($roleCheck->num_rows === 0) {
        $errors[] = "❌ Invalid role selected.";
    }

    // Handle Image Upload (Save Path Instead of BLOB)
    $imagePath = "uploads/default-user.png"; // Default image path

    if ($image['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            $errors[] = "❌ Only JPG, PNG, and GIF images are allowed.";
        } else {
            // Ensure `uploads/` folder exists
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique file name
            $imagePath = $uploadDir . time() . "_" . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $errors[] = "❌ Error uploading the image.";
            }
        }
    }

    // Insert into Database if No Errors
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, country, address, image, role_id, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssssi", $name, $email, $passwordHash, $phone, $country, $address, $imagePath, $role_id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ User added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>" . implode("<br>", $errors) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Add New User</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="add_user.php" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone number" required>
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" name="country" id="country" class="form-control" placeholder="Enter country" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" placeholder="Enter address" required></textarea>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Profile Image (JPG, PNG, GIF only)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg, image/png, image/gif">
            </div>

            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="view.php" class="btn btn-secondary">Back to Users</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
