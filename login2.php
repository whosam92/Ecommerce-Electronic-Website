
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="loginStyle.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .error { color: red; font-size: 12px; }
    </style>
</head>
<body>
    <section class="forms-section">
        <h1 class="section-title">Animated Forms</h1>
        <div class="forms">
            <div class="form-wrapper is-active">
                <button type="button" class="switcher switcher-login">
                    Login
                    <span class="underline"></span>
                </button>
                <form class="form form-login" action="login.php" method="POST">
                    <fieldset>
                        <legend>Please enter your email and password for login.</legend>
                        <div class="input-block">
                            <label for="login-email">E-mail</label>
                            <input id="login-email" name="email" type="email" >
                        </div>
                        <div class="input-block">
                            <label for="login-password">Password</label>
                            <input id="login-password" name="password" type="password" >
                        </div>
                    </fieldset>
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>
            <div class="form-wrapper">
                <button type="button" class="switcher switcher-signup">
                    Sign Up
                    <span class="underline"></span>
                </button>
                <form class="form form-signup" action="signup.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <fieldset>
                        <legend>Please enter your details for sign up.</legend>
                        <div class="input-block">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text">
                            <small class="error" id="name-error"></small>
                        </div>
                        <div class="input-block">
                            <label for="signup-email">E-mail</label>
                            <input id="signup-email" name="email" type="email" >
                            <small class="error" id="email-error"></small>
                        </div>
                        <div class="input-block">
                            <label for="phone">Phone Number</label>
                            <input id="phone" name="phone" type="text" >
                            <small class="error" id="phone-error"></small>
                        </div>
                        <div class="input-block">
                            <label for="country">Country</label>
                            <input id="country" name="country" type="text" >
                        </div>
                        <div class="input-block">
                            <label for="signup-password">Password</label>
                            <input id="signup-password" name="password" type="password" >
                            <small class="error" id="password-error"></small>
                        </div>
                        <div class="input-block">
                            <label for="signup-password-confirm">Confirm Password</label>
                            <input id="signup-password-confirm" name="confirm_password" type="password" >
                        </div>
                        <div class="input-block">
                            <label for="image">Upload Image</label>
                            <input id="image" name="image" type="file" >
                            <small class="error" id="image-error"></small>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn-signup">Continue</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        function validateForm() {
            let valid = true;
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("signup-email").value;
            let phone = document.getElementById("phone").value;
            let password = document.getElementById("signup-password").value;
            let confirmPassword = document.getElementById("signup-password-confirm").value;
            let image = document.getElementById("image").files[0];

            if (!/^[a-zA-Z ]+$/.test(name)) {
                document.getElementById("name-error").textContent = "Name must contain only letters and spaces";
                valid = false;
            } else {
                document.getElementById("name-error").textContent = "";
            }

            if (!/^[\w.-]+@[\w.-]+\.\w{2,}$/.test(email)) {
                document.getElementById("email-error").textContent = "Invalid email format";
                valid = false;
            } else {
                document.getElementById("email-error").textContent = "";
            }

            if (!/^(077|078|079)\d{7}$/.test(phone)) {
                document.getElementById("phone-error").textContent = "Phone must start with 077, 078, or 079 and have 10 digits";
                valid = false;
            } else {
                document.getElementById("phone-error").textContent = "";
            }

            if (!/(?=.*[A-Z])(?=.*\d).{6,}/.test(password)) {
                document.getElementById("password-error").textContent = "Password must be at least 6 characters, include a capital letter and a number";
                valid = false;
            } else {
                document.getElementById("password-error").textContent = "";
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                valid = false;
            }

            if (image) {
                let validTypes = ["image/jpeg", "image/png", "image/jpg"];
                if (!validTypes.includes(image.type) || image.size > 2 * 1024 * 1024) {
                    document.getElementById("image-error").textContent = "Invalid image format or size exceeds 2MB";
                    valid = false;
                } else {
                    document.getElementById("image-error").textContent = "";
                }
            }
            return valid;
        }
    </script>


<script>
const switchers = [...document.querySelectorAll('.switcher')]

switchers.forEach(item => {
	item.addEventListener('click', function() {
		switchers.forEach(item => item.parentElement.classList.remove('is-active'))
		this.parentElement.classList.add('is-active')
	})
})
</script>
</body>
</html>
