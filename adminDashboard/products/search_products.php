<?php
include '../db.php';

if (isset($_POST['query'])) {
    $query = $conn->real_escape_string($_POST['query']);

    $sql = "SELECT p.*, c.name AS category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.name LIKE '%$query%' OR p.id LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";

            echo "<td>";
            if (!empty($product['image']) && file_exists($product['image'])) {
                echo '<img src="' . $product['image'] . '" alt="Product Image" class="img-thumbnail product-image">';
            } else {
                echo '<img src="uploads/default-product.png" alt="Default Image" class="img-thumbnail product-image">';
            }
            echo "</td>";

            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $product['description'] . "</td>";
            echo "<td>" . $product['price'] . "</td>";
            echo "<td>" . $product['stock'] . "</td>";
            echo "<td>" . $product['category_name'] . "</td>";

            echo "<td>
                    <a href='edit_product.php?id=" . $product['id'] . "' class='btn-icon edit' title='Edit'>
                        <i class='bi bi-pencil-square'></i>
                    </a>
                    <button class='btn-icon delete' data-id='" . $product['id'] . "' title='Delete'>
                        <i class='bi bi-trash-fill'></i>
                    </button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>No products found</td></tr>";
    }
}
?>
