<?php
include '../db.php';

// Validate and sanitize the ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to an integer to prevent SQL injection

    // Check if the user exists before attempting deletion
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if ($result->num_rows > 0) {
        // Delete the user
        if ($conn->query("DELETE FROM users WHERE id = $id")) {
            $message = "User deleted successfully!";
        } else {
            $message = "Error: Could not delete the user. " . $conn->error;
        }
    } else {
        $message = "Error: User not found.";
    }
} else {
    $message = "Invalid user ID.";
}

// Redirect back to the users page with a message
header("Location: view.php?message=" . urlencode($message));
exit;
?>
