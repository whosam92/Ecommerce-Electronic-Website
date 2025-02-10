<?php
session_start();
include './adminDashboard/db.php';

$errors = [];
$success_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        
        // Login Handling
        if ($_POST['action'] == 'login') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email)) {
                $errors['email'] = "Email is required!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format!";
            }

            if (empty($password)) {
                $errors['password'] = "Password is required!";
            }

            if (empty($errors)) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $errors = []; // Clear errors after successful login
                    header("Location: " . ($user['role_id'] == 2 ? "adminDashboard/index.php" : "index-4.php"));
                    exit;
                } else {
                    $errors['login'] = "Invalid email or password!";
                }
            }
        }

        // Registration Handling
        elseif ($_POST['action'] == 'register') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phone = trim($_POST['phone']);
            $country = trim($_POST['country']);
            $address = trim($_POST['address']);
            $imagePath = "uploads/default.png";

            // Name Validation
            if (empty($name) || strlen($name) < 4) {
                $errors['name'] = "Name must be at least 4 characters!";
            }

            // Email Validation
            if (empty($email)) {
                $errors['email'] = "Email is required!";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format!";
            }

            // Password Validation
            if (empty($password)) {
                $errors['password'] = "Password is required!";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $password)) {
                $errors['password'] = "Password must have at least 8 chars, 1 uppercase, 1 number, and 1 special char!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            }

            // Phone Validation
            if (empty($phone) || !preg_match('/^07[789][0-9]{7}$/', $phone)) {
                $errors['phone'] = "Invalid Jordanian phone number!";
            }

            // Country Validation
            if (empty($country)) {
                $errors['country'] = "Country is required!";
            } elseif (strtolower($country) !== 'jordan') {
                $errors['country'] = "Only users from Jordan can register!";
            }

            // Address Validation
            if (empty($address)) {
                $errors['address'] = "Address is required!";
            }

            // File Upload Handling
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/";
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileType, $allowedTypes)) {
                    $errors['image'] = "Only JPG, JPEG, PNG & GIF formats are allowed!";
                } elseif ($_FILES['image']['size'] > 2000000) {
                    $errors['image'] = "Image size must be less than 2MB!";
                } else {
                    move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
                    $imagePath = $targetFilePath;
                }
            }

            // Insert into database if no errors
            if (empty($errors)) {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, country, address, image, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->bind_param("sssssss", $name, $email, $hashed_password, $phone, $country, $address, $imagePath);

                if ($stmt->execute()) {
                    $success_message = "Registration successful!";
                    $errors = []; // Clear errors after successful registration
                } else {
                    $errors['register'] = "Registration failed!";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration System</title>
    <style>
        * {
            margin: 0;
            padding: 5px;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-container {
            display: flex;
            gap: 30px;
            justify-content: space-between;
        }

        .registered-customers,
        .new-customers {
            flex: 1;
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ff6b00;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .forgot-password {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-login,
        .btn-register,
        .btn-clear {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-login,
        .btn-register {
            background-color: #ff6b00;
            color: white;
        }

        .btn-clear {
            background-color: #f4f4f4;
            color: #333;
        }

        .checkboxes {
            display: flex;
            gap: 20px;
        }

        .phone-error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="registered-customers">
                <h2>Registered Customers</h2>
                <p>If you have an account with us, please log in.</p>
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                    </div>
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            <div class="new-customers">
                <h2>New Customers</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="register">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Full Name" >
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Phone Number (077, 078, or 079)" pattern="07[789][0-9]{7}" required>
                        <div class="phone-error">Invalid phone number</div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="country" placeholder="Country Name (optional)">
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" placeholder="Address">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirmPassword" placeholder="Confirm Password" >
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" accept="image/*" class="form-control mb-2">
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-register">Register</button>
                        <button type="reset" class="btn-clear">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('input[name="phone"]');
            const phoneError = document.querySelector('.phone-error');

            phoneInput.addEventListener('input', function() {
                const isValid = /^07[789][0-9]{7}$/.test(this.value);
                phoneError.style.display = isValid ? 'none' : 'block';
                this.setCustomValidity(isValid ? '' : 'Invalid phone number');
            });
        });
    </script>
</body>
</html>

