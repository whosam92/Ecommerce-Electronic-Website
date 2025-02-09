<?php
include 'config.php'; 

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("SELECT image FROM users WHERE id = :id");
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && !empty($user['image'])) {
            header("Content-Type: image/jpeg"); 
            echo $user['image'];
            exit;
        } else {
            header("Content-Type: image/png");
            readfile("profile-placeholder.png");
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
