<?php
include '../db.php';

$message = '';

// Fetch user details based on ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>User not found.</div>";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = trim($conn->real_escape_string($_POST['name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];
    $phone = trim($conn->real_escape_string($_POST['phone']));
    $country = trim($conn->real_escape_string($_POST['country']));
    $address = trim($conn->real_escape_string($_POST['address']));
    $role_id = intval($_POST['role_id']);
    $image = $_FILES['image'];

    // Regex validation
    $errors = [];
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "❌ Name must contain only letters and spaces.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "❌ Invalid email format.";
    }
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "❌ Phone number must be 10 digits.";
    }
    if (!empty($password) && strlen($password) < 8) {
        $errors[] = "❌ Password must be at least 8 characters.";
    }

    // Handle image upload (only if a new image is uploaded)
    $imagePath = $user['image']; // Keep old image by default

    if ($image['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExtensions)) {
            $errors[] = "❌ Only JPG, PNG, and GIF images are allowed.";
        } else {
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $imagePath = $uploadDir . time() . "_" . basename($image['name']);
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $errors[] = "❌ Error uploading the image.";
            }
        }
    }

    // Update database if no errors
    if (empty($errors)) {
        $passwordUpdate = !empty($password) ? ", password = '" . password_hash($password, PASSWORD_BCRYPT) . "'" : '';

        $stmt = $conn->prepare("UPDATE users SET 
                                name = ?, 
                                email = ?, 
                                phone = ?, 
                                country = ?, 
                                address = ?, 
                                role_id = ?, 
                                image = ? 
                                $passwordUpdate 
                                WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $email, $phone, $country, $address, $role_id, $imagePath, $id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ User updated successfully!</div>";
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
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Edit User</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" name="country" id="country" class="form-control" value="<?php echo htmlspecialchars($user['country']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option value="1" <?php echo $user['role_id'] == 1 ? 'selected' : ''; ?>>Admin</option>
                    <option value="2" <?php echo $user['role_id'] == 2 ? 'selected' : ''; ?>>User</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Profile Image</label>
                <input type="file" name="image" id="image" class="form-control">
                <div class="mt-2">
                    <?php if (!empty($user['image']) && file_exists($user['image'])): ?>
                        <img src="<?php echo $user['image']; ?>" alt="Profile Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                    <?php else: ?>
                        <img src="uploads/default-user.png" alt="Default Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="view.php" class="btn btn-secondary">Back to Users</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
