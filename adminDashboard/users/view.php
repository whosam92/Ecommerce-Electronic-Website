<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f9f9f9;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 95%;
            margin: auto;
        }

        h2 {
            background: linear-gradient(90deg, #007bff, #ff7f00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            text-align: center;
        }

        .table {
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: linear-gradient(90deg, #007bff, #ff7f00);
            color: white;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 127, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff, #ff7f00);
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #ff7f00, #007bff);
        }

        .btn-icon {
            border: none;
            background: none;
            font-size: 1.3rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-icon.edit {
            color: #007bff;
        }

        .btn-icon.delete {
            color: #ff7f00;
        }

        .btn-icon:hover {
            transform: scale(1.2);
        }

        /* Image Styling */
        .user-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h2 class="mb-4">Users</h2>

        <!-- Search & Navigation Buttons -->
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="search" class="form-control w-50" placeholder="Search by ID or Name...">
            <div>
                <a href="../index.php" class="btn btn-secondary"><i class="bi bi-house-door-fill"></i> Back Home</a>
                <a href="add_user.php" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Add User</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php
                    include '../db.php'; 

                    $result = $conn->query("SELECT * FROM users");

                    if ($result->num_rows > 0) {
                        while ($user = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $user['id'] . "</td>";

                            // Display Image from File Path
                            echo "<td>";
                            if (!empty($user['image']) && file_exists($user['image'])) {
                                echo '<img src="' . $user['image'] . '" alt="User Image" class="img-thumbnail user-image">';
                            } else {
                                echo '<img src="uploads/default-user.png" alt="Default Image" class="img-thumbnail user-image">';
                            }
                            echo "</td>";

                            echo "<td>" . $user['name'] . "</td>";
                            echo "<td>" . $user['email'] . "</td>";
                            echo "<td>" . $user['phone'] . "</td>";
                            echo "<td>" . $user['country'] . "</td>";

                            echo "<td>
                                    <a href='edit_user.php?id=" . $user['id'] . "' class='btn-icon edit' title='Edit'>
                                        <i class='bi bi-pencil-square'></i>
                                    </a>
                                    <button class='btn-icon delete' data-id='" . $user['id'] . "' title='Delete'>
                                        <i class='bi bi-trash-fill'></i>
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll(".delete");
            deleteButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const userId = this.getAttribute("data-id");
                    Swal.fire({
                        title: "Are you sure?",
                        text: "This action cannot be undone!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#ff7f00",
                        cancelButtonColor: "#007bff",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "delete_user.php?id=" + userId;
                        }
                    });
                });
            });

            // AJAX Search Function
            $("#search").on("keyup", function () {
                let searchValue = $(this).val();
                $.ajax({
                    url: "search_users.php",
                    method: "POST",
                    data: { query: searchValue },
                    success: function (data) {
                        $("#userTable").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
