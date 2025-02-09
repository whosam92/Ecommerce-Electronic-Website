<?php
include '../adminDashboard/db.php'; // Database connection

// Fetch admin data (assuming admin ID is 1 for this example)
$adminId = 1; // Replace with the logged-in admin ID
$result = $conn->query("SELECT * FROM users WHERE id = $adminId");
$admin = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($conn->real_escape_string($_POST['name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $phone = trim($conn->real_escape_string($_POST['phone']));
    $updatedImagePath = $admin['image']; // Default to current image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $updatedImagePath = $filePath;
            }
        }
    }

    // Update admin profile
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $updatedImagePath, $adminId);

    if ($stmt->execute()) {
        $successMessage = "Profile updated successfully!";
        $admin['name'] = $name;
        $admin['email'] = $email;
        $admin['phone'] = $phone;
        $admin['image'] = $updatedImagePath;
    } else {
        $errorMessage = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            background: linear-gradient(90deg,rgb(71, 72, 73), #ff7f00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(90deg,rgb(67, 67, 67), #ff7f00);
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg,rgb(117, 116, 115), #ff7f00);
        }

        .user-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Profile</h2>
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>
        <form method="POST" action="admin_profile.php" enctype="multipart/form-data">
            <div class="text-center">
                <input type="file" name="image" class="form-control mt-3">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($admin['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($admin['phone']) ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Profile</button>
     
                <a href="index.php" class="btn btn-secondary"><i class="bi bi-house-door-fill"></i> Back Home</a>
                </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
