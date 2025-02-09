<?php

session_start();
include './adminDashboard/db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "error|You must be logged in to add to cart!";
    exit();
}

// Validate POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($product_id <= 0 || $quantity <= 0) {
        echo "error|Invalid product or quantity!";
        exit();
    }

    // Check if product already exists in cart
    $check_cart_query = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check_cart_query->bind_param("ii", $user_id, $product_id);
    $check_cart_query->execute();
    $cart_result = $check_cart_query->get_result();

    if ($cart_result->num_rows > 0) {
        // Update existing cart item
        $update_cart_query = $conn->prepare("UPDATE cart SET quantity = quantity + ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?");
        $update_cart_query->bind_param("iii", $quantity, $user_id, $product_id);
        $update_cart_query->execute();
    } else {
        // Insert new cart item
        $insert_cart_query = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $insert_cart_query->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_cart_query->execute();
    }

    echo "success|Item added successfully!";
    exit();
}
?>
<?php
    // Check if product already exists in cart
    $check_cart_query = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check_cart_query->bind_param("ii", $user_id, $product_id);
    $check_cart_query->execute();
    $cart_result = $check_cart_query->get_result();

    if ($cart_result->num_rows > 0) {
        // Update existing cart item
        $update_cart_query = $conn->prepare("UPDATE cart SET quantity = quantity + ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?");
        $update_cart_query->bind_param("iii", $quantity, $user_id, $product_id);
        $update_cart_query->execute();
    } else {
        // Insert new cart item
        $insert_cart_query = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $insert_cart_query->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_cart_query->execute();
    }
    ?>

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Item Added!',
            text: 'Your item has been successfully added to the cart.',
            background: '#ffb74d', // Orange background
            confirmButtonColor: '#ff9800'
        }).then(() => {
            window.location.href = document.referrer; // Redirect back to the previous page
        });
    </script>";
    exit();

?>
