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
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Nivo-slider css -->
    <link rel="stylesheet" href="lib/css/nivo-slider.css" />
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="css/core.css" />
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css" />
    <!-- Theme main style -->
    <link rel="stylesheet" href="style.css" />
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css" />
    <!-- User style -->
    <link rel="stylesheet" href="css/custom.css" />

    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="css/style-customizer.css" />
    <link href="#" data-style="styles" rel="stylesheet" />
    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-3.11.2.min.js"></script>
    <style>
      /* Banner Item Adjustments */
.banner-item {
    height: 100%; /* Make it take the full height of its container */
    display: flex; /* Center content */
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.banner-img img {
    max-width: 100%;
    height: auto; /* Ensure image scales properly */
    border-radius: 8px;
}

/* Ensure Banner and Upcoming Product Sections Match */
.col-lg-4,
.col-lg-8 {
    display: flex;
    align-items: stretch; /* Ensures equal height */
}

.banner-item,
.up-comming-pro {
    flex: 1; /* Makes the inner content fill the parent height */
}

/* Title Styling */
.section-title {
    margin-bottom: 40px;
    margin-top:40px;
}

.section-title h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    text-transform: uppercase;
    margin-bottom: 10px;
}

/* Styling the HR */
.title-hr {
    width: 250px;
    height: 3px;
    background-color: #ff7f00; /* Bright orange color */
    margin: 0 auto; /* Center it */
    border: none;
    border-radius: 3px;
}


/* second section style */
/* By Categories Section */
.by-brand-section {
    margin-top: 60px; /* Space from previous section */
    margin-bottom: 60px; /* Space below this section */
    padding: 40px 0; /* Inner padding */
    background-color: #f8f9fa; /* Light gray background */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Category Cards */
.category-card {
    background: #fff; /* White background for each card */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Card shadow */
    padding: 20px; /* Space inside the card */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover effect */
}

.category-card:hover {
    transform: translateY(-5px); /* Lift on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
}

.category-card h5 {
    font-size: 1.5rem; /* Larger font size for category titles */
    font-weight: bold;
    color: #222; /* Dark font color */
    margin-bottom: 10px;
}

.category-card p {
    font-size: 1rem; /* Standard size for descriptions */
    color: #555; /* Muted gray color */
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-title h2 {
        font-size: 2rem; /* Adjust font size for smaller screens */
    }

    .up-comming-pro,
    .category-card {
        padding: 15px; /* Reduce padding for smaller screens */
    }

    .up-comming-pro-info h3,
    .category-card h5 {
        font-size: 1.3rem; /* Smaller font size */
    }

    .up-comming-pro-info p,
    .category-card p {
        font-size: 0.9rem; /* Adjust description size */
    }
}


    </style>
  </head>

  <body>
    <?php include('nav.php')?>
    <!-- ==================================================== -->


      <!-- START MOBILE MENU AREA -->
     

      <!-- ============================= -->

      <!-- START SLIDER AREA -->
      <div class="slider-area bg-3 bg-opacity-black-60 ptb-150 mb-80 section">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="slider-desc-3 slider-desc-4 text-center">
                <div
                  class="wow fadeInUp"
                  data-wow-duration="1s"
                  data-wow-delay="1s"
                >
                  <h1 class="slider2-title-2 cd-headline clip is-full-width">
                    <span>Best Deal ever</span>
                    <span class="cd-words-wrapper">
                      <b class="is-visible">laptops</b>
                      <b>smartphons</b>
                      <b>watches</b>
                    </span>
                  </h1>
                </div>
                <div
                  class="wow fadeInUp"
                  data-wow-duration="1s"
                  data-wow-delay="1.5s"
                >
                  <h2 class="slider2-title-3">all Electronics Here</h2>
                </div>
                <div
                  class="slider-button wow fadeInUp"
                  data-wow-duration="1s"
                  data-wow-delay="2.5s"
                >
                  <a href="shop.php" class="button extra-small button-white">
                    <span class="text-uppercase">Buy now</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END SLIDER AREA -->


<!-- =========================================================== -->

<?php
// Database connection
include './adminDashboard/db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the most recent product as the "Upcoming Product"
$upcoming_product_sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 1"; // Assuming 'created_at' is the timestamp column
$upcoming_product_result = $conn->query($upcoming_product_sql);

// Fetch a random product for the "Banner Product"
$banner_product_sql = "SELECT * FROM products ORDER BY RAND() LIMIT 1";
$banner_product_result = $conn->query($banner_product_sql);
?>

<!-- Start page content -->
<section id="page-content" class="page-wrapper section">
    <!-- UP COMING PRODUCT SECTION START -->
    <div class="up-comming-product-section mb-80">
        <div class="container">
            <!-- Title Section -->
            <div class="section-title text-center">
                <h2>Upcoming Products</h2>
                <hr class="title-hr">
            </div>

            <div class="row">
                <!-- Upcoming Product -->
                <div class="col-lg-8">
                    <div class="up-comming-pro gray-bg clearfix">
                        <?php if ($upcoming_product_result->num_rows > 0): ?>
                            <?php while ($upcoming_product = $upcoming_product_result->fetch_assoc()): ?>
                                <div class="up-comming-pro-img f-left">
                                    <a href="single-product.php?id=<?= $upcoming_product['id'] ?>">
                                        <img src="<?= htmlspecialchars($upcoming_product['image']) ?>" alt="<?= htmlspecialchars($upcoming_product['name']) ?>" />
                                    </a>
                                </div>
                                <div class="up-comming-pro-info f-left">
                                    <h3><a href="single-product.php?id=<?= $upcoming_product['id'] ?>"><?= htmlspecialchars($upcoming_product['name']) ?></a></h3>
                                    <p><?= htmlspecialchars($upcoming_product['description']) ?></p>
                                    <div class="up-comming-time">
                                        
                                    <div>
                                    <div id="countdown">
    <span class="cdown minutes"><span class="time-count" id="minutes">30</span> <p>Mint</p></span>
    <span class="cdown second"><span class="time-count" id="seconds">00</span> <p>Sec</p></span>
</div>

<script>
    function startCountdown(durationInMinutes) {
        let endTime = localStorage.getItem("countdownEndTime");

        if (!endTime) {
            // Set end time if it's not already stored
            endTime = new Date().getTime() + durationInMinutes * 60 * 1000;
            localStorage.setItem("countdownEndTime", endTime);
        } else {
            endTime = parseInt(endTime, 10); // Convert stored value to number
        }

        function updateCountdown() {
            let now = new Date().getTime();
            let remainingTime = endTime - now;

            if (remainingTime <= 0) {
                document.getElementById("countdown").innerHTML = "<p>Offer expired!</p>";
                localStorage.removeItem("countdownEndTime"); // Clear countdown when it expires
                clearInterval(timer);
                return;
            }

            let minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
        }

        let timer = setInterval(updateCountdown, 1000);
        updateCountdown();
    }

    startCountdown(30); // Starts the countdown for 30 minutes
</script>

                                        </div> <!-- Replace with your countdown logic -->
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No upcoming products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Banner Product -->
                <div class="col-lg-4 d-block d-md-none d-lg-block">
                    <?php if ($banner_product_result->num_rows > 0): ?>
                        <?php while ($banner_product = $banner_product_result->fetch_assoc()): ?>
                            <div class="banner-item banner-1">
                                <div class="ribbon-price">
                                    <span>$ <?= htmlspecialchars($banner_product['price']) ?></span>
                                </div>
                                <div class="banner-img">
                                    <a href="single-product.php?id=<?= $banner_product['id'] ?>">
                                        <img src="<?= htmlspecialchars($banner_product['image']) ?>" alt="<?= htmlspecialchars($banner_product['name']) ?>" />
                                    </a>
                                </div>
                                <div class="banner-info">
                                    <h3><a href="single-product.php?id=<?= $banner_product['id'] ?>"><?= htmlspecialchars($banner_product['name']) ?></a></h3>
                                    <!-- <ul class="banner-featured-list">
                                        <li><i class="zmdi zmdi-check"></i><span>High Quality</span></li>
                                        <li><i class="zmdi zmdi-check"></i><span>Best Price</span></li>
                                        <li><i class="zmdi zmdi-check"></i><span>Trusted Brand</span></li>
                                        <li><i class="zmdi zmdi-check"></i><span>Customer Satisfaction</span></li>
                                        <li><i class="zmdi zmdi-check"></i><span>Innovative Design</span></li>
                                    </ul> -->
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No banner products available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- UP COMING PRODUCT SECTION END -->
</section>

<?php $conn->close(); ?>


        <!-- =====================================BY BRAND========================================== -->

<!-- BY CATEGORIES SECTION START-->
<div class="by-brand-section mb-80">
    <div class="container">
        <!-- Title Section -->
        <div class="section-title text-center">
            <h2>By Categories</h2>
            <hr class="title-hr">
        </div>
        <div class="row">
            <?php
            // Database connection
            include './adminDashboard/db.php';

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch categories from the database
            $sql = "SELECT id, name, description FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop through the categories
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <h5 class="text-uppercase mb-2"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No categories found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>
<!-- BY CATEGORIES SECTION END-->







        <!-- BY BRAND SECTION END =========================================================================-->

        
        <!-- FEATURED PRODUCT SECTION START -->
<!-- FEATURED PRODUCT SECTION START -->
<div class="featured-product-section mb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-40">
                    <h2 class="uppercase ">Featured Products</h2>
                    <hr class="title-hr">


                </div>
                <div class="featured-product">
                    <div class="active-featured-product slick-arrow-2">
                        <?php
                        include './adminDashboard/db.php';

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT id, name, description, price, image FROM products LIMIT 8";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="product-item" style="height: 400px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div class="product-img" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <a href="single-product.php?id=<?php echo $row['id']; ?>">
                                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid" style="max-height: 100%; width: auto;" />
                                        </a>
                                    </div>
                                    <div class="product-info" style="text-align: center; padding: 10px;">
                                        <h6 class="product-title">
                                            <a href="single-product.php?id=<?php echo $row['id']; ?>">
                                                <?php echo htmlspecialchars($row['name']); ?>
                                            </a>
                                        </h6>
                                        <div class="pro-rating">
                                            <a href="#"><i class="zmdi zmdi-star"></i></a>
                                            <a href="#"><i class="zmdi zmdi-star"></i></a>
                                            <a href="#"><i class="zmdi zmdi-star"></i></a>
                                            <a href="#"><i class="zmdi zmdi-star-half"></i></a>
                                            <a href="#"><i class="zmdi zmdi-star-outline"></i></a>
                                        </div>
                                        <h3 class="pro-price">$<?php echo htmlspecialchars($row['price']); ?></h3>
                                       
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p class='text-center'>No featured products found.</p>";
                        }

                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FEATURED PRODUCT SECTION END -->

<!-- Slick Slider Initialization -->
<script>
    $(document).ready(function () {
        $('.active-featured-product').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>

<!-- FEATURED PRODUCT SECTION END -->

        <!-- FEATURED PRODUCT SECTION END -->
<br>
<br>
         <!-- ==================================================================================================== -->

        <!-- PRODUCT TAB SECTION START -->
     <!-- PRODUCT TAB SECTION START -->
<div class="product-tab-section mb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title text-start mb-40">
                    <h2 class="uppercase">Product List</h2>
                    
                    
                    <h6>There are many variations of passages of brands available,</h6>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-tab pro-tab-menu text-end">
                    <!-- Nav tabs -->
                    <ul class="nav">
                        <?php
                        // Database connection
                        include './adminDashboard/db.php';

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch categories from the database
                        $category_sql = "SELECT id, name FROM categories";
                        $category_result = $conn->query($category_sql);

                        if ($category_result->num_rows > 0) {
                            $first = true; // To make the first tab active
                            while ($category = $category_result->fetch_assoc()) {
                                $active_class = $first ? "active" : "";
                                echo '<li>
                                    <a class="' . $active_class . '" href="#category-' . $category['id'] . '" data-bs-toggle="tab">' . htmlspecialchars($category['name']) . '</a>
                                </li>';
                                $first = false; // Ensure only the first tab is active
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <?php
            $category_result = $conn->query($category_sql); // Re-fetch categories for tab content
            if ($category_result->num_rows > 0) {
                $first = true; // To make the first tab content active
                while ($category = $category_result->fetch_assoc()) {
                    $active_class = $first ? "active show" : "";
                    echo '<div id="category-' . $category['id'] . '" class="tab-pane fade ' . $active_class . '">
                        <div class="row">';

                    // Fetch products for the current category (limit to 8)
                    $product_sql = "SELECT id, name, price, image FROM products WHERE category_id = " . $category['id'] . " LIMIT 8";
                    $product_result = $conn->query($product_sql);

                    if ($product_result->num_rows > 0) {
                        while ($product = $product_result->fetch_assoc()) {
                            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="product-item">
                                    <div class="product-img" style="height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f8f8; border-radius: 8px;">
                                        <a href="single-product.php?id=' . $product['id'] . '">
                                            <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </a>
                                    </div>
                                    <div class="product-info text-center" style="padding: 15px; background: #ffffff; border: 1px solid #e6e6e6; border-radius: 8px;">
                                        <h6 class="product-title" style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">
                                            <a href="single-product.php?id=' . $product['id'] . '" style="text-decoration: none; color: #333;">' . htmlspecialchars($product['name']) . '</a>
                                        </h6>
                                        <h3 class="pro-price" style="font-size: 18px; color: #000;">$' . htmlspecialchars($product['price']) . '</h3>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p class="text-center">No products found in this category.</p>';
                    }

                    echo '</div>';
                    echo '<div class="text-center mt-4">
                            <a href="shop.php?category_id=' . $category['id'] . '" 
                               class="btn-show-all" 
                               style="background-color: #ff7f00; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold; transition: background-color 0.3s;">
                                Show All Products
                            </a>
                          </div>';
                    echo '</div>';
                    $first = false; // Ensure only the first tab content is active
                }
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>
<!-- PRODUCT TAB SECTION END -->
 <br>
 <br>




              <!-- ========================================================================== -->
              
        <!-- PRODUCT TAB SECTION END -->







        <!-- ===================================================================================== -->

        <!-- BLOG SECTION START -->
       <!-- FEATURED COLLECTION SECTION START -->
<div class="blog-section mb-50">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title text-center mb-40">
          <h2 class="uppercase" style="font-size: 32px;">Our Collections</h2>
          <hr class="title-hr">

         
        </div>
        <div class="blog">
          <div class="active-blog">
            <!-- Featured Collection 1 -->
            <div class="blog-item">
              <img src="img/blog/2.jpg" alt="Smartphones" />
              <div class="blog-desc">
                <h5 class="blog-title">
                  <a href="shop-smartphones.html">Explore the Latest Smartphones</a>
                </h5>
                <p>
                  Stay connected with the best smartphones that combine sleek designs, powerful performance, and advanced features.
                </p>
              </div>
            </div>

            <!-- Featured Collection 2 -->
            <div class="blog-item">
              <img src="img/blog/1.jpg" alt="Laptops" />
              <div class="blog-desc">
                <h5 class="blog-title">
                  <a href="shop-laptops.html">High-Performance Laptops</a>
                </h5>
                <p>
                  Upgrade your workflow with our collection of laptops that deliver speed, efficiency, and premium quality.
                </p>
              </div>
            </div>

            <!-- Featured Collection 3 -->
            <div class="blog-item">
              <img src="img/blog/3.jpg" alt="Watches" />
              <div class="blog-desc">
                <h5 class="blog-title">
                  <a href="shop-watches.html">Elegant and Modern Watches</a>
                </h5>
                <p>
                  Discover stylish and innovative watches designed to complement your daily lifestyle and elevate your look.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- FEATURED COLLECTION SECTION END -->

      </section>
      <!-- End page content -->
       <!--  -->
       
<!-- footer -->
      <?php include('footer.php')?>
      <!-- footer -->

      <!-- START QUICKVIEW PRODUCT -->
      <div id="quickview-wrapper">
        <!-- Modal -->
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button
                  type="button"
                  class="close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="modal-product clearfix">
                  <div class="product-images">
                    <div class="main-image images">
                      <img alt="" src="img/product/quickview.jpg" />
                    </div>
                  </div>
                  <!-- .product-images -->

                  <div class="product-info">
                    <h1>Aenean eu tristique</h1>
                    <div class="price-box-3">
                      <div class="s-price-box">
                        <span class="new-price">Â£160.00</span>
                        <span class="old-price">Â£190.00</span>
                      </div>
                    </div>
                    <a href="single-product-left-sidebar.html" class="see-all"
                      >See all features</a
                    >
                    <div class="quick-add-to-cart">
                      <form method="post" class="cart">
                        <div class="numbers-row">
                          <input
                            type="number"
                            id="french-hens"
                            value="3"
                            min="1"
                          />
                        </div>
                        <button class="single_add_to_cart_button" type="submit">
                          Add to cart
                        </button>
                      </form>
                    </div>
                    <div class="quick-desc">
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Nam fringilla augue nec est tristique auctor. Donec non
                      est at libero.
                    </div>
                    <div class="social-sharing">
                      <div class="widget widget_socialsharing_widget">
                        <h3 class="widget-title-modal">Share this product</h3>
                        <ul class="social-icons clearfix">
                          <li>
                            <a
                              class="facebook"
                              href="#"
                              target="_blank"
                              title="Facebook"
                            >
                              <i class="zmdi zmdi-facebook"></i>
                            </a>
                          </li>
                          <li>
                            <a
                              class="google-plus"
                              href="#"
                              target="_blank"
                              title="Google +"
                            >
                              <i class="zmdi zmdi-google-plus"></i>
                            </a>
                          </li>
                          <li>
                            <a
                              class="twitter"
                              href="#"
                              target="_blank"
                              title="Twitter"
                            >
                              <i class="zmdi zmdi-twitter"></i>
                            </a>
                          </li>
                          <li>
                            <a
                              class="pinterest"
                              href="#"
                              target="_blank"
                              title="Pinterest"
                            >
                              <i class="zmdi zmdi-pinterest"></i>
                            </a>
                          </li>
                          <li>
                            <a class="rss" href="#" target="_blank" title="RSS">
                              <i class="zmdi zmdi-rss"></i>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!-- .product-info -->
                </div>
                <!-- .modal-product -->
              </div>
              <!-- .modal-body -->
            </div>
            <!-- .modal-content -->
          </div>
          <!-- .modal-dialog -->
        </div>
        <!-- END Modal -->
      </div>
      <!-- END QUICKVIEW PRODUCT -->

      
    </div>
    <!-- Body main wrapper end -->

    <!-- Placed JS at the end of the document so the pages load faster -->

    <!-- jquery latest version -->
    <script src="js/vendor/jquery-3.6.0.min.js"></script>
    <script src="js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <!-- Bootstrap framework js -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- jquery.nivo.slider js -->
    <script src="lib/js/jquery.nivo.slider.js"></script>
    <!-- All js plugins included in this file. -->
    <script src="js/plugins.js"></script>
    <!-- Main js file that contents all jQuery plugins activation. -->
    <script src="js/main.js"></script>
  </body>
</html>
