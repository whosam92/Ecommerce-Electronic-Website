<?php
// إعدادات قاعدة البيانات
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ecom_project2';

try {
    // إنشاء اتصال باستخدام PDO
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
