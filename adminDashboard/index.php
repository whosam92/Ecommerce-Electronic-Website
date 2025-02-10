<?php
include '../adminDashboard/db.php';
// Include database connection

// Fetch counts
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$productCount = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$categoryCount = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
$totalIncome = $conn->query("SELECT SUM(total_price) AS total_income FROM orders")->fetch_assoc()['total_income'];


// Fetch recent orders
$recentOrders = $conn->query("SELECT o.id, u.name AS user_name, o.total_price, o.created_at 
                              FROM orders o
                              LEFT JOIN users u ON o.user_id = u.id
                              ORDER BY o.created_at DESC LIMIT 5");

// Fetch recent customers
$recentCustomers = $conn->query("SELECT name, country, image FROM users ORDER BY created_at DESC LIMIT 5");
?>

<!-- admin check if logged or not starts here -->
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page with an alert
    echo "<script>alert('Please log in to access this page.'); window.location.href = 'login.php';</script>";
    exit();
}

// Check if the user has admin privileges (role_id = 2)
if ($_SESSION['role_id'] !== 2) {
    // If the user is not an admin, redirect to login page with an alert
    echo "<script>alert('Access denied! Admins only.'); window.location.href = '../login.php';</script>";
    exit();
}
?>
<!-- admin check ends here  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <!-- <ion-icon name="logo-apple"></ion-icon> -->
                        </span>
                        <span class="title">Ecom Website</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../adminDashboard/users/view.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Users</span>
                    </a>
                </li>
                <li>
                    <a href="../adminDashboard/products/view_products.php">
                        <span class="icon">
                            <ion-icon name="cart-outline"></ion-icon>
                        </span>
                        <span class="title">Products</span>
                    </a>
                </li>
                <li>
                    <a href="../adminDashboard/orders/view_order.php">
                        <span class="icon">
                            <ion-icon name="receipt-outline"></ion-icon>
                        </span>
                        <span class="title">Orders</span>
                    </a>
                </li>
                <li>
                    <a href="../adminDashboard/categories/view_categories.php">
                        <span class="icon">
                            <ion-icon name="layers-outline"></ion-icon>
                        </span>
                        <span class="title">Categories</span>
                    </a>
                </li>
                <li>
                    <a href="../adminDashboard/discounts/view_discounts.php">
                        <span class="icon">
                            <ion-icon name="pricetag-outline"></ion-icon>
                        </span>
                        <span class="title">Discounts</span>
                    </a>
                </li>
                <li>
                <li>
                <li>
    <a href="../adminDashboard/dailyRevenue/view_daily_revenue.php">
        <span class="icon">
            <ion-icon name="stats-chart-outline"></ion-icon>
        </span>
        <span class="title">Daily Revenue</span>
    </a>
</li>
<li>
                    <a href="../index-4.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                
                <div class="user">

    <!-- Display the user's profile picture and name -->
   
    
    <!-- will connect fill admin profile later on  -->

                </div>
            </div>

            <!-- Dynamic Cards start -->
<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?= $userCount ?></div>
            <div class="cardName">Users</div>
        </div>
        <div class="iconBx">
            <ion-icon name="people-outline"></ion-icon>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers"><?= $productCount ?></div>
            <div class="cardName">Products</div>
        </div>
        <div class="iconBx">
            <ion-icon name="cart-outline"></ion-icon>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers"><?= $orderCount ?></div>
            <div class="cardName">Orders</div>
        </div>
        <div class="iconBx">
            <ion-icon name="receipt-outline"></ion-icon>
        </div>
    </div>
    <div class="card">
        <div>
            <div class="numbers"><?= $categoryCount ?></div>
            <div class="cardName">Categories</div>
        </div>
        <div class="iconBx">
            <ion-icon name="layers-outline"></ion-icon>
        </div>
    </div>
    <div class="card">
    <div>
        <div class="numbers">$<?= number_format($totalIncome, 2) ?></div>
        <div class="cardName">Total Income</div>
    </div>
    <div class="iconBx">
        <ion-icon name="cash-outline"></ion-icon>
    </div>
</div>

</div>
         <!-- Dynamic Cards ends -->


            <!-- Recent Orders -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                        <a href="../adminDashboard/orders/view_order.php" class="btn">View All</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>Order ID</td>
                                <td>User</td>
                                <td>Total Price</td>
                                <td>Created At</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $recentOrders->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= $order['user_name'] ?></td>
                                    <td>$<?= $order['total_price'] ?></td>
                                    <td><?= $order['created_at'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Customers -->
                <div class="recentCustomers">
    <div class="cardHeader">
        <h2>Recent Users</h2>
    </div>
    <table>
        <?php while ($user = $recentCustomers->fetch_assoc()) : ?>
            <tr>
                <td width="60px">
                    <div class="imgBx">
                        <?php
                        // Determine the correct image path
                        $imagePath = !empty($user['image']) && file_exists(filename: "../" . $user['image']) 
                            ? "/adminDashboard/users/uploads/" . $user['image'] 
                            : "../uploads/default-user.png"; // Fallback to a default image
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="User Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                    </div>
                </td>
                <td>
                    <h4><?= htmlspecialchars($user['name']) ?><br>
                        <span><?= htmlspecialchars($user['country']) ?></span>
                    </h4>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>





    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
