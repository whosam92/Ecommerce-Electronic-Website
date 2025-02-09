<?php
include './adminDashboard/db.php'; // Include database connection

$output = '';
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Default SQL query to fetch all products
$sql = "SELECT * FROM products";

// If a search query is provided, modify the query to filter by product name
if (!empty($query)) {
    $sql .= " WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("s", $searchTerm);
} else {
    // Fetch all products if no search query is provided
    $stmt = $conn->prepare($sql);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="product-item border p-3 rounded shadow-sm">
                <div class="product-img">
                    <a href="single-product.php?id=' . $row['id'] . '">
                        <img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="img-fluid" />
                    </a>
                </div>
                <div class="product-info mt-3">
                    <h6 class="product-title">
                        <a href="single-product.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a>
                    </h6>
                    <h3 class="pro-price">$' . number_format($row['price'], 2) . '</h3>
                </div>
            </div>
        </div>';
    }
} else {
    $output = '<p class="text-center">No products found!</p>';
}

echo $output;
?>
