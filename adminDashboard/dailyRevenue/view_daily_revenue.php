<?php
include '../db.php'; // Database connection

// Fetch daily revenue data
$dailyRevenue = $conn->query("
    SELECT DATE(created_at) AS order_date, SUM(total_price) AS daily_revenue 
    FROM orders 
    GROUP BY DATE(created_at) 
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Daily Revenue</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
        }
        .table {
            margin-bottom: 0;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cardHeader {
            background: #007bff;
            color: #fff;
            padding: 15px 20px;
            font-size: 18px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 20px 0;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="card">
            <div class="cardHeader">
                <h2 class="text-center mb-0">Daily Revenue</h2>
            </div>
            <div class="p-4">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Date</th>
                            <th>Total Revenue ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dailyRevenue->num_rows > 0): ?>
                            <?php while ($row = $dailyRevenue->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                                    <td>$<?= number_format($row['daily_revenue'], 2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="no-data">No revenue data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <a href="../index.php" class="btn btn-secondary px-4">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
