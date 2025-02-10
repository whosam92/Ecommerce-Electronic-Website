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

<?php
$limit = 8; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of products
$total_result = $conn->query("SELECT COUNT(*) as total FROM products");
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch paginated products
$query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<?php
// session_start();
include './adminDashboard/db.php'; // Include the database connection file

// Default values for sorting and price range
$order_by = "created_at DESC";
$min_price = 0;
$max_price = 3000; // Adjust this to your maximum product price

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

// Pagination setup
$limit = 8; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of products that match the filters
$total_query = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE price BETWEEN ? AND ?");
$total_query->bind_param("dd", $min_price, $max_price);
$total_query->execute();
$total_result = $total_query->get_result();
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch products for the current page
$query = $conn->prepare("SELECT * FROM products WHERE price BETWEEN ? AND ? ORDER BY $order_by LIMIT ? OFFSET ?");
$query->bind_param("ddii", $min_price, $max_price, $limit, $offset);
$query->execute();
$result = $query->get_result();
?>



    <style>
        /* Pagination Styles */
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

        /* Card Styles */
        .product-item {
            height: 350px; /* Set a consistent height for all product cards */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-img img {
            max-height: 200px; /* Limit image height for consistency */
            object-fit: contain;
            width: 100%;
        }

        .product-info {
            text-align: center;
        }

        .pro-price {
            font-size: 18px;
            color: #ff7f00;
        }
    </style>
    <title>Shop Page</title>
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
                                    <li><a href="index.html">Home</a></li>
                                    <li>Check Our Products</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="page-content" class="page-wrapper section">
            <div class="shop-section mb-80">
                <div class="container">
                    <div class="row">
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
                                            <?php if ($total_products > 0): ?>
                                                <?php while ($product = $result->fetch_assoc()): ?>
                                                    <div class="col-lg-3 col-md-4 mb-4">
                                                        <div class="product-item border p-3 rounded shadow-sm">
                                                            <div class="product-img">
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
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <p class="text-center">No products found!</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="pagination-container mt-4">
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <?php if ($page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?page=<?= $page - 1 ?>&sort=<?= isset($_GET['sort']) ? $_GET['sort'] : '' ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                                        <a class="page-link" href="?page=<?= $i ?>&sort=<?= isset($_GET['sort']) ? $_GET['sort'] : '' ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if ($page < $total_pages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?page=<?= $page + 1 ?>&sort=<?= isset($_GET['sort']) ? $_GET['sort'] : '' ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- End Pagination -->
                                </div>
                            </div>
                        </div>

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
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    data: { query: query },
                    success: function(data) {
                        $("#product-list").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
