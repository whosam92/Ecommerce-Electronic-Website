<?php
include '../db.php';

$output = '';
$query = isset($_POST['query']) ? $conn->real_escape_string($_POST['query']) : '';

$sql = "SELECT o.*, u.name AS user_name 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id LIKE '%$query%' OR u.name LIKE '%$query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$order['id']}</td>
            <td>{$order['user_name']}</td>
            <td>{$order['total_price']}</td>
            <td>{$order['created_at']}</td>
            <td>{$order['updated_at']}</td>
            <td>
                <a href='edit_order.php?id={$order['id']}' class='btn-icon edit' title='Edit'><i class='bi bi-pencil-square'></i></a>
                <button class='btn-icon delete' data-id='{$order['id']}' title='Delete'><i class='bi bi-trash-fill'></i></button>
            </td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='6' class='text-center'>No orders found</td></tr>";
}

echo $output;
?>
