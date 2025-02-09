<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT name, email, phone, address, image, created_at FROM users WHERE id = :id");
    $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    $order_stmt = $conn->prepare("SELECT orders.id, orders.created_at, orders.total_price, user_history.status 
                                  FROM orders 
                                  JOIN user_history ON orders.id = user_history.order_id 
                                  WHERE orders.user_id = :id 
                                  ORDER BY orders.created_at DESC");
    $order_stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $order_stmt->execute();
    $orders = $order_stmt->fetchAll(PDO::FETCH_OBJ);

    $total_orders_stmt = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders WHERE user_id = :id");
    $total_orders_stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $total_orders_stmt->execute();
    $total_orders = $total_orders_stmt->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Subas || My Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/core.css" />
    <link rel="stylesheet" href="css/shortcode/shortcodes.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <style>
        /* تحسين مظهر الملف الشخصي */
        .profile-img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ddd;
            margin-bottom: 15px;
        }

        .profile-container {
            padding: 40px;
            text-align: center;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .profile-container p {
            font-size: 1.5rem;
        }

        .profile-container .btn {
            font-size: 1.3rem;
            padding: 12px 25px;
            margin: 5px;
            width: 180px;
        }

        /* تحسين جدول الطلبات */
        .table-responsive {
            margin-top: 20px;
        }

        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #343a40 !important;
            color: white !important;
            font-size: 1.2rem;
            text-align: center;
            padding: 15px;
        }

        .table td {
            font-size: 1.1rem;
            vertical-align: middle;
            text-align: center;
            padding: 12px;
        }

        /* تحسين ألوان حالات الطلب */
        .badge {
            font-size: 1rem;
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        /* تحسين زر التفاصيل */
        .btn-view {
            background-color: #ff8000; /* برتقالي */
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-view:hover {
            background-color: #cc6600; /* لون أغمق عند التمرير */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include("nav.php") ?>
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">My Account</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>My Account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات المستخدم -->
        <div id="page-content" class="page-wrapper section">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="profile-container">
                            <img src="display_image.php?id=<?= htmlspecialchars($user_id); ?>" alt="User Image" class="profile-img">
                            <h2><?= htmlspecialchars($user->name); ?></h2>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user->email); ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($user->phone); ?></p>
                            <p><strong>Address:</strong> <?= htmlspecialchars($user->address); ?></p>
                            <p><strong>Joined On:</strong> <?= htmlspecialchars($user->created_at); ?></p>
                            <p><strong>Total Orders:</strong> <?= htmlspecialchars($total_orders->total_orders ?? 0); ?></p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="update_profile.php" class="btn btn-warning">Update Profile</a>
                                <a href="logout.php" class="btn btn-danger">Logout</a>
                            </div>
                        </div>

                        <!-- جدول الطلبات -->
                        <h3 class="mt-5 text-center">Order History (<?= htmlspecialchars($total_orders->total_orders ?? 0); ?>)</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mt-3">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <?php
                                            $status_class = '';
                                            switch ($order->status) {
                                                case 'Completed':
                                                    $status_class = 'success';
                                                    break;
                                                case 'Pending':
                                                    $status_class = 'warning';
                                                    break;
                                                case 'Cancelled':
                                                    $status_class = 'danger';
                                                    break;
                                                default:
                                                    $status_class = 'secondary';
                                                    break;
                                            }
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($order->id); ?></td>
                                            <td><?= htmlspecialchars($order->created_at); ?></td>
                                            <td><?= htmlspecialchars($order->total_price); ?> $</td>
                                            <td><span class="badge badge-<?= $status_class ?>"><?= htmlspecialchars($order->status); ?></span></td>
                                            <td><a href="order_details.php?order_id=<?= htmlspecialchars($order->id); ?>" class="btn-view">View Details</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("footer.php") ?>
    </div>
</body>
</html>
