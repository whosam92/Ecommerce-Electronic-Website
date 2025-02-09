<?php
// Start session
session_start();

// Include database connection
require 'adminDashboard/db.php'; // Adjust path to your connection file

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in to view your cart.");
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$sql = "SELECT c.quantity, p.id AS product_id, p.name, p.description, p.price, p.image 
        FROM cart c 
        INNER JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate the total price
$total_price = 0;
foreach ($cartItems as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .quantity-controls button {
            margin: 0 5px;
            padding: 5px 10px;
        }
        .quantity-controls span {
            font-weight: bold;
            font-size: 16px;
        }
        .total-section h3 {
            margin: 10px 0;
        }
        .total-before {
            text-decoration: line-through;
            color: orange;
            font-size: 18px;
        }
        .total-after {
            font-size: 22px;
            color: green;
            font-weight: bold;
        }
        /* Align the button to the right */
.checkout-container {
    text-align: right;
    margin-top: 20px;
}

/* Orange button styling */
.checkout-btn {
    color: white;
 
}
.checkout-btn:hover {
    background-color: rgb(255, 127, 0);
    color: white;
}

.cart-btn {
    border-radius: 6px;
    font-size: 16px;
    padding: 0px 15px;
    height: 30px;
    line-height: 22px;
    background-color: rgb(255, 127, 0);
    font-size: 33px;
color:white;
}

.quantity-controls span {
    font-weight: bold;
    font-size: 21px;
}



    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navigation -->
    <?php include('nav.php'); ?>

    <!-- Shopping Cart Section -->
    <div class="container mt-5">
        <h1 class="text-center">Shopping Cart</h1>
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <tr data-product-id="<?= $item['product_id'] ?>">
                                <td>
                                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="Product Image" width="100">
                                    <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                                    <p><?= htmlspecialchars($item['description']) ?></p>
                                </td>
                                <td class="product-price">$<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <div class="quantity-controls">
                                        <button class="cart-btn btn-sm btn-primary quantity-decrease">-</button>
                                        <span class="quantity"><?= htmlspecialchars($item['quantity']) ?></span>
                                        <button class="cart-btn btn-sm btn-primary quantity-increase">+</button>
                                    </div>
                                </td>
                                <td class="product-total">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                <td>
                                    <a href="remove_cart_item.php?product_id=<?= htmlspecialchars($item['product_id']) ?>" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($cartItems)): ?>
            <!-- Coupon Section -->
            <div class="coupon-section mt-4">
                <form id="apply-coupon-form">
                    <label for="coupon">Enter Coupon Code:</label>
                    <input type="text" id="coupon" name="coupon" class="form-control w-25 d-inline">
                    <button type="button" id="apply-coupon-btn" class="submit-btn-1 mt-20 btn-hover-1">Apply</button>
                </form>
                <div id="coupon-message" class="mt-2"></div>
            </div>

            <!-- Total Section -->
            <div class="total-section mt-4">
                <h3 id="total-before-wrapper" style="display: none;">Total Before Coupon: <span class="total-before">$<?= number_format($total_price, 2) ?></span></h3>
                <h3>Total: <span id="total-price" class="total-after">$<?= number_format($total_price, 2) ?></span></h3>
                                
            </div>

            <!-- Proceed to Checkout Button (Right-Aligned) -->
<div class="checkout-container">
<button class="submit-btn-1 mt-20 btn-hover-1" type="submit"><a href="PaymentPage.php" class="checkout-btn">Proceed to Checkout</a></button>
    
</div>
<br>

        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for Quantity and Coupon Update -->
    <script>
        $(document).ready(function () {
            // Handle quantity increase
            $('.quantity-increase').click(function () {
                const row = $(this).closest('tr');
                const productId = row.data('product-id');

                $.ajax({
                    url: 'insert_to_cart.php',
                    method: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const newQuantity = response.new_quantity;
                            row.find('.quantity').text(newQuantity);
                            const price = parseFloat(row.find('.product-price').text().replace('$', ''));
                            row.find('.product-total').text('$' + (price * newQuantity).toFixed(2));
                            updateTotalPrice();
                        } else {
                            alert(response.error || 'An error occurred.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while updating the quantity.');
                    }
                });
            });

            // Handle quantity decrease
            $('.quantity-decrease').click(function () {
                const row = $(this).closest('tr');
                const productId = row.data('product-id');

                const currentQuantity = parseInt(row.find('.quantity').text());
                if (currentQuantity <= 1) {
                    return; // Prevent decreasing below 1
                }

                $.ajax({
                    url: 'reduce_cart_item.php',
                    method: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const newQuantity = response.new_quantity;
                            row.find('.quantity').text(newQuantity);
                            const price = parseFloat(row.find('.product-price').text().replace('$', ''));
                            row.find('.product-total').text('$' + (price * newQuantity).toFixed(2));
                            updateTotalPrice();
                        } else {
                            alert(response.error || 'An error occurred.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while updating the quantity.');
                    }
                });
            });

            // Handle coupon application
            $('#apply-coupon-btn').click(function () {
                const coupon = $('#coupon').val();

                $.ajax({
                    url: 'apply_coupon.php',
                    method: 'POST',
                    data: { coupon: coupon },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#total-before-wrapper').show();
                            $('#total-price').text('$' + response.discounted_price.toFixed(2));
                            $('#coupon-message').text('Coupon applied successfully!').css('color', 'green');
                        } else {
                            $('#coupon-message').text(response.error).css('color', 'red');
                        }
                    },
                    error: function () {
                        $('#coupon-message').text('An error occurred while applying the coupon.').css('color', 'red');
                    }
                });
            });

            // Update the total price dynamically
            function updateTotalPrice() {
                let total = 0;
                $('.product-total').each(function () {
                    total += parseFloat($(this).text().replace('$', ''));
                });
                $('.total-before').text('$' + total.toFixed(2));
                $('#total-price').text('$' + total.toFixed(2)); // Reset total-after coupon if no coupon applied
            }
        });
    </script>
</body>
</html>
