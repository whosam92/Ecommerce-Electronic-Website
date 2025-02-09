<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    try {
        $password_stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $password_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $password_stmt->execute();
        $stored_password = $password_stmt->fetchColumn();

        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            if ($current_password !== $stored_password) {
                $message = '<p style="color: red;">Current password is incorrect!</p>';
            } elseif ($new_password !== $confirm_password) {
                $message = '<p style="color: red;">New passwords do not match!</p>';
            } elseif (strlen($new_password) < 8) {
                $message = '<p style="color: red;">New password must be at least 8 characters long!</p>';
            } else {
                $update_password_stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $update_password_stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
                $update_password_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                $update_password_stmt->execute();
                $message = '<p style="color: green;">Password updated successfully!</p>';
            }
        }

        if (empty($message)) {
            $sql = "UPDATE users SET name = :name, email = :email, address = :address, phone = :phone WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $full_name = $first_name . ' ' . $last_name;
            $stmt->bindParam(':name', $full_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $message = '<p style="color: green;">Profile updated successfully!</p>';
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

try {
    $sql = "SELECT name, email, address, phone FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Profile</h2>
        <?= $message; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars(explode(' ', $user['name'])[0]); ?>" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars(explode(' ', $user['name'])[1] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea name="address" class="form-control" required><?= htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>" required>
            </div>

            <h4>Change Password</h4>
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password:</label>
                <input type="password" name="current_password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="my-account.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
