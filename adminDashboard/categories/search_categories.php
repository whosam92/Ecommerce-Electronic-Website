<?php
include '../db.php';

$output = '';
$query = isset($_POST['query']) ? $conn->real_escape_string($_POST['query']) : '';

$sql = "SELECT * FROM categories WHERE name LIKE '%$query%' OR id LIKE '%$query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($category = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$category['id']}</td>
            <td>{$category['name']}</td>
            <td>{$category['description']}</td>
            <td>{$category['created_at']}</td>
            <td>
                <a href='edit_category.php?id={$category['id']}' class='btn-icon edit'><i class='bi bi-pencil-square'></i></a>
                <button class='btn-icon delete' data-id='{$category['id']}'><i class='bi bi-trash-fill'></i></button>
            </td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='5' class='text-center'>No categories found</td></tr>";
}

echo $output;
?>
