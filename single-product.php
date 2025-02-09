<?php
session_start();
include './adminDashboard/db.php'; // Database connection

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Product ID.");
}

$product_id = intval($_GET['id']);

// Fetch product details
$product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
$product_query->bind_param("i", $product_id);
$product_query->execute();
$product_result = $product_query->get_result();

if ($product_result->num_rows === 0) {
    die("Product not found!");
}

$product = $product_result->fetch_assoc();

// Fetch product reviews with user names
$review_query = $conn->prepare("
    SELECT reviews.*, users.name 
    FROM reviews 
    JOIN users ON reviews.user_id = users.id 
    WHERE reviews.product_id = ? 
    ORDER BY reviews.created_at DESC
");
$review_query->bind_param("i", $product_id);
$review_query->execute();
$reviews = $review_query->get_result();

// Calculate average rating
$avg_rating_query = $conn->prepare("SELECT COALESCE(AVG(rating), 0) AS avg_rating FROM reviews WHERE product_id = ?");
$avg_rating_query->bind_param("i", $product_id);
$avg_rating_query->execute();
$avg_rating_result = $avg_rating_query->get_result();
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'];

// Fetch related products
$related_query = $conn->prepare("
    SELECT id, name, image, price 
    FROM products 
    WHERE category_id = ? AND id != ? 
    ORDER BY RAND() 
    LIMIT 4
");
$related_query->bind_param("ii", $product['category_id'], $product_id);
$related_query->execute();
$related_products = $related_query->get_result();
?>


<!-- html code -->

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 Library -->


    <style>
        button, a.btn {
            background-color: #FF7F00;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        button:hover, a.btn:hover {
            background-color: rgb(243, 177, 111);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .product-section {
            padding: 20px;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .review-section {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .related-products-section {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-section h1 {
            color: orange;
            font-weight: bold;
        }

        .rating span {
            font-size: 20px;
        }

        .input-group button {
            background-color: #FF7F00;
            color: white;
            border: none;
        }

        .input-group button:hover {
            background-color: rgb(243, 177, 111);
        }

        .breadcrumbs-section {
            padding: 15px 0;
            background: #f7f7f7;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include('nav.php'); ?>

    <!-- Breadcrumb -->
    <div class="breadcrumbs-section">
        <div class="container">
            <ul class="breadcrumb-list d-flex">
                <li><a href="index.html">Home</a></li>
                <li>/</li>
                <li>Product Details</li>
            </ul>
        </div>
    </div>

    <!-- Product Details -->
    <div class="container">
        <div class="product-section">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
                </div>
                <div class="col-lg-6">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <h3>Price: <span style="color: green;">$<?php echo number_format($product['price'], 2); ?></span></h3>
                    <h4>Average Rating:</h4>
                    <div class="rating">
                        <?php for ($i = 0; $i < round($avg_rating); $i++): ?>
                            <span class="fa fa-star" style="color: orange;"></span>
                        <?php endfor; ?>
                        <?php for ($i = round($avg_rating); $i < 5; $i++): ?>
                            <span class="fa fa-star" style="color: #ddd;"></span>
                        <?php endfor; ?>
                        <span style="color: orange; font-weight: bold;"> <?php echo round($avg_rating, 1); ?>/5</span>
                    </div>
                    <p style="margin-top: 20px; line-height: 1.6; font-size: 16px;"> <?php echo htmlspecialchars($product['description']); ?></p>
                    <form method="POST" action="add_to_cart_single_product.php" style="margin-top: 20px;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity" style="margin-right: 10px; font-weight: bold;">Quantity:</label>
                        <div class="input-group w-50 mb-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="5" class="form-control text-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                        </div>
                        <button class="submit-btn-1 mt-20 btn-hover-1" type="submit">Add to Cart</button>
                     </form>
                     


                </div>
            </div>
        </div>

       <!-- Reviews Section -->
<div class="review-section">
    <h2 class="my-4">Customer Reviews</h2>
    <?php if ($reviews->num_rows > 0): ?>
        <div class="reviews-container">
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-item p-3 mb-4" style="border: 1px solid #ddd; border-radius: 10px;">
                    <div class="review-header d-flex align-items-center mb-2">
                        <div>
                            <h5 class="mb-0" style="font-weight: bold;">Reviewed by: <?php echo htmlspecialchars($review['name']); ?></h5>
                            <small style="color: #888;">On: <?php echo htmlspecialchars($review['created_at']); ?></small>
                        </div>
                    </div>
                    <div class="review-body">
                        <p class="mb-1" style="font-size: 16px; line-height: 1.6;">
                            <?php echo htmlspecialchars($review['review_text']); ?>
                        </p>
                        <div class="rating" style="margin-top: 10px;">
                            Rating:
                            <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                <span class="fa fa-star" style="color: orange;"></span>
                            <?php endfor; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                                <span class="fa fa-star" style="color: #ddd;"></span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No reviews yet. Be the first to review this product!</p>
    <?php endif; ?>

    <!-- Add Review Form -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="add-review-form mt-5">
            <h3 class="mb-3">Write a Review</h3>
            <form method="POST" action="add_review_single_page.php">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <div class="form-group mb-3">
                    <label for="rating">Your Rating:</label>
                    <div class="rating-stars" style="font-size: 24px;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="fa fa-star" style="color: #ddd; cursor: pointer;" onclick="setRating(<?php echo $i; ?>)" id="star-<?php echo $i; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0" required>
                </div>
                <div class="form-group mb-3">
                    <label for="review_text">Your Comment:</label>
                    <textarea name="review_text" id="review_text" rows="4" class="form-control" placeholder="Write your review here..." required></textarea>
                </div>
                <button class="submit-btn-1 mt-20 btn-hover-1" type="submit">Submit Review</button>

            </form>
        </div>
        
        <script>
            function setRating(rating) {
                document.getElementById('rating').value = rating;
                for (let i = 1; i <= 5; i++) {
                    const star = document.getElementById(`star-${i}`);
                    if (i <= rating) {
                        star.style.color = 'orange';
                    } else {
                        star.style.color = '#ddd';
                    }
                }
            }
        </script>
    <?php else: ?>
        <p class="mt-4">Please <a href="login.php" style="color: orange; text-decoration: underline;">log in</a> to leave a review.</p>
    <?php endif; ?>
</div>



        <!-- Related Products Section -->
      
    </div>



    <!-- Footer -->
    <?php include('footer.php'); ?>

    <script>
        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let value = parseInt(quantityInput.value, 10);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            let value = parseInt(quantityInput.value, 10);
            if (value < 5) {
                quantityInput.value = value + 1;
            }
        }

  // Add to cart logic with SweetAlert2
  document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const productId = document.querySelector('input[name="product_id"]').value;
            const quantity = document.getElementById('quantity').value;


            fetch('add_to_cart_single_product.php', {
                method: 'POST',
                body: new URLSearchParams({ product_id: productId, quantity: quantity }),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'cart.php'; // Redirect to cart page
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your item has been successfully added to the cart.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
        });
    </script>

<!-- <script>
    function addToCart() {
        const productId = document.querySelector('input[name="product_id"]').value;
        const quantity = document.getElementById('quantity').value;

        fetch('add_to_cart_single_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success alert
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                    });
                } else {
                    // Show error alert
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            });
    }
</script> -->


</body>

</html>
