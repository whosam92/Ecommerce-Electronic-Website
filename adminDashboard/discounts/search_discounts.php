<?php
include '../db.php';

$query = isset($_POST['query']) ? $_POST['query'] : '';
$output = '';

$sql = "SELECT * FROM discounts WHERE discountCode LIKE '%$query%' OR order_id LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($discount = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$discount['id']}</td>
            <td>{$discount['discountCode']}</td>
            <td>{$discount['percentage']}%</td>
            <td>{$discount['order_id']}</td>
            <td>{$discount['created_at']}</td>
            <td>
                <a href='edit_discount.php?id={$discount['id']}' class='btn-icon edit'><i class='bi bi-pencil-square'></i></a>
                <button class='btn-icon delete' data-id='{$discount['id']}'><i class='bi bi-trash-fill'></i></button>
            </td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='6' class='text-center'>No discounts found</td></tr>";
}

echo $output;
