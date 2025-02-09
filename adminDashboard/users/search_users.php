<?php
include '../db.php';

$query = isset($_POST['query']) ? trim($_POST['query']) : '';

$sql = "SELECT * FROM users WHERE id LIKE '%$query%' OR name LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td><img src='" . (!empty($user['image']) ? $user['image'] : "uploads/default-user.png") . "' class='img-thumbnail user-image'></td>";
        echo "<td>" . $user['name'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['phone'] . "</td>";
        echo "<td>" . $user['country'] . "</td>";
        echo "<td>
                <a href='edit_user.php?id=" . $user['id'] . "' class='btn-icon edit'><i class='bi bi-pencil-square'></i></a>
                <button class='btn-icon delete' data-id='" . $user['id'] . "'><i class='bi bi-trash-fill'></i></button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No results found</td></tr>";
}
?>
