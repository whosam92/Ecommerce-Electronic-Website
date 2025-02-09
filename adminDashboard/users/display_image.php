<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conn->prepare("SELECT image FROM users WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $query->store_result();
    $query->bind_result($imageData);
    $query->fetch();

    if (!empty($imageData)) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            header("Content-Type: $mimeType");
            echo $imageData;
        } else {
            header("Content-Type: image/png");
            readfile("default-user.png");
        }
    } else {
        // Default image if no user image is found
        header("Content-Type: image/png");
        readfile("default-user.png");
    }
}
?>
