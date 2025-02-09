<?php
session_start();
include './adminDashboard/db.php'; // Include the mysqli database connection file

// Default sorting and price range values
$order_by = "created_at DESC";
$min_price = 0;
$max_price = 3000; // Adjust to your maximum product price

// Handle sorting
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    if ($sort == 'price_low_high') {
        $order_by = "price ASC";
    } elseif ($sort == 'price_high_low') {
        $order_by = "price DESC";
    }
}

// Handle price range
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $min_price = floatval($_GET['min_price']);
    $max_price = floatval($_GET['max_price']);
}

// Fetch products
$query = $conn->prepare("SELECT * FROM products WHERE price BETWEEN ? AND ? ORDER BY $order_by");
$query->bind_param("dd", $min_price, $max_price);
$query->execute();
$result = $query->get_result();
$total_products = $result->num_rows; // Total products for display
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Subas || Shop</title>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-3.11.2.min.js"></script>

    <style>
    .pagination .page-item .page-link {
    color: #ff7f00;
}

.pagination .page-item.active .page-link {
    background-color: #ff7f00 !important;
    border-color: #d66a00 !important;
    color: white;
}

.pagination .page-item.disabled .page-link {
    background-color: #e0e0e0 !important; 
    border-color: #e0e0e0 !important;
    color: #a0a0a0 !important;
    pointer-events: none;
    opacity: 0.6;
}


</style>

</head>

<body>
    <div class="wrapper">
        <?php include('nav.php'); ?>

        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Check Our Products</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index-4.php">Home</a></li>
                                    <li>Check Our Products</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!--pagination query-->
        <?php
include './adminDashboard/db.php'; 
$products_per_page = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $products_per_page;

$total_result = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $total_result->fetch_assoc()['total'];
$total_pages = min(3, ceil($total_products / $products_per_page));

$stmt = $conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $products_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!--pagination query end-->

        <div id="page-content" class="page-wrapper section">
            <div class="shop-section mb-80">
                <div class="container">
                    <div class="row">
                    <div class="col-lg-3 order-lg-1 order-2">
                        <aside class="widget-search mb-30">
                            <form id="search-form">
                                <input type="text" id="search-box" placeholder="Search here..." />
                            </form>
                        </aside>

                        <aside class="widget shop-filter box-shadow mb-30">
                            <h6 class="widget-title border-left mb-20">Price</h6>
                            <div class="price_filter">
                                <div class="price_slider_amount">
                                    <p id="price-range-display"></p>
                                </div>
                                <div id="slider-range"></div>
                            </div>
                        </aside>
                    </div>
                        <div class="col-lg-9 order-lg-2 order-1">
                            <div class="shop-content">
                                <div class="shop-option box-shadow mb-30 clearfix">
                                    <ul class="nav shop-tab f-left" role="tablist">
                                        <li>
                                            <a class="active" href="#grid-view" data-bs-toggle="tab"><i class="zmdi zmdi-view-module"></i></a>
                                        </li>
                                    </ul>
                                    <div class="short-by f-left text-center">
                                        <span>Sort by :</span>
                                        <select id="sort-by">
                                            <option value="price_low_high" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_low_high') ? 'selected' : '' ?>>Price: Low to High</option>
                                            <option value="price_high_low" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_high_low') ? 'selected' : '' ?>>Price: High to Low</option>
                                        </select>
                                    </div>
                                    <div class="showing f-right text-end">
                                        <span>Showing : <?= $total_products ?> products.</span>
                                    </div>
                                </div>


                                

                                <div class="tab-content">
                                    <div id="grid-view" class="tab-pane active show" role="tabpanel">
                                        <div class="row" id="product-list">
                                            <?php if (count($products) > 0): ?>
                                                <?php foreach ($products as $product): ?>
                                                    <div class="col-lg-3 col-md-4 mb-4">
                                                        <div class="product-item border p-3 rounded shadow-sm">
                                                            <div class="product-img" style="height: 170px;">
                                                                <a href="single-product.php?id=<?= $product['id'] ?>">
                                                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid" />
                                                                </a>
                                                            </div>
                                                            <div class="product-info mt-3">
                                                                <h6 class="product-title">
                                                                    <a href="single-product.php?id=<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
                                                                </h6>
                                                                <h3 class="pro-price">$<?= number_format($product['price'], 2) ?></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-center">No products found!</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>

                <!--pagination-->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
    </div>

    <script src="js/vendor/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Price Slider
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 3000,
                values: [<?= $min_price ?>, <?= $max_price ?>],
                slide: function(event, ui) {
                    $("#price-range-display").text("$" + ui.values[0] + " - $" + ui.values[1]);
                },
                change: function(event, ui) {
                    const min = ui.values[0];
                    const max = ui.values[1];
                    window.location.href = "?min_price=" + min + "&max_price=" + max + "&sort=" + $("#sort-by").val();
                }
            });

            $("#price-range-display").text("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));

            // Sorting
            $("#sort-by").change(function() {
                const sortValue = $(this).val();
                window.location.href = "?sort=" + sortValue + "&min_price=" + $("#slider-range").slider("values", 0) + "&max_price=" + $("#slider-range").slider("values", 1);
            });

            // Search Box with AJAX
            $("#search-box").keyup(function() {
                const query = $(this).val();
                $.ajax({
                    url: "search_shop_page.php",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $("#product-list").html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>