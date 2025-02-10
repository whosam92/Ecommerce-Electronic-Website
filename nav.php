<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Subas || Home-4</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/icon/favicon.png" />

    <!-- All CSS Files -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="lib/css/nivo-slider.css" />
    <link rel="stylesheet" href="css/core.css" />
    <link rel="stylesheet" href="css/shortcode/shortcodes.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <link rel="stylesheet" href="css/style-customizer.css" />
    <link href="#" data-style="styles" rel="stylesheet" />

    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>

<?php
// check and Start session
if (session_status() === PHP_SESSION_NONE) {
//   session_start();
}


// Include database connection
require 'adminDashboard/db.php'; // Adjust the path to your connection file

// Initialize cart quantity
$cart_quantity = 0;

// Ensure user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch total quantity of items in the cart for the logged-in user
    $sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $cart_quantity = $row['total_quantity'] ?? 0; // Default to 0 if no items in the cart
    $stmt->close();
}
?>

<!-- Body main wrapper start -->
<div class="wrapper">
    <!-- START HEADER AREA -->
    <header class="header-area header-wrapper">
        <div class="header-top-bar plr-185">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-6 d-none d-md-block"></div>
                    <div class="col-lg-6 col-md-6">
                        <div class="top-link clearfix">

                        <!-- top nav icons and login logout switch code  -->
                        <ul class="link f-right">
    <li>
        <a href="my-account.php">
            <i class="zmdi zmdi-account"></i>
            My Account
        </a>
    </li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li>
            <a href="logout.php">
                <i class="zmdi zmdi-lock-open"></i>
                Logout
            </a>
        </li>
    <?php else: ?>
        <li>
            <a href="login.php">
                <i class="zmdi zmdi-lock"></i>
                Login
            </a>
        </li>
    <?php endif; ?>
</ul>

<!-- logic switch login logout here top nav ends here  -->


</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- header-middle-area -->
        <div class="header-middle-area plr-185">
            <div class="container-fluid">
                <div class="full-width-mega-dropdown">
                    <div class="row">
                        <div class="col-lg-2 col-md-4">
                            <div class="logo">
                                <a href="index-4.php">
                                    <img src="img/logo/logo.png" alt="main logo" />
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-8 d-none d-lg-block">
                            <nav id="primary-menu">
                                <ul class="main-menu text-center">
                                    <li><a href="index-4.php">Home</a></li>
                                    <li><a href="my-account.php">My Account</a></li>
                                    <li class="mega-parent"><a href="shop.php">Products</a></li>
                                    <li><a href="about.php">About us</a></li>
                                    <li><a href="contact.php">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- header-search & total-cart -->
                        <div class="col-lg-2 col-md-8">
                            <div class="search-top-cart f-right">
                                <div class="total-cart f-left">
                                    <div class="total-cart-in">
                                        <div class="cart-toggler">
                                            <a href="cart.php">
                                                <!-- Dynamic cart quantity -->
                                                <span class="cart-quantity"><?= htmlspecialchars($cart_quantity) ?></span><br />
                                                <span class="cart-icon">
                                                    <i class="zmdi zmdi-shopping-cart-plus"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER AREA -->

    <!-- AJAX Script to Update Cart Quantity in Navbar -->
    <script>
        $(document).ready(function () {
            function updateCartQuantity() {
                $.ajax({
                    url: 'fetch_cart_quantity.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('.cart-quantity').text(response.quantity);
                        }
                    },
                    error: function () {
                        console.error('Failed to fetch cart quantity.');
                    }
                });
            }

            // Call the function initially and set an interval to update dynamically
            updateCartQuantity();
            setInterval(updateCartQuantity, 5000); // Update every 5 seconds
        });
    </script>
</div>
