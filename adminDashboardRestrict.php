<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    echo "<script>
        alert('Access denied! Please log in as an admin.');
        window.location.href = '/Ecommerce-Electronic-Website/login.php';
    </script>";
    exit();
}
?>
