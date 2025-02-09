<?php
include './adminDashboard/db.php'; // Include the MySQLi database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required POST inputs are set
    if (isset($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['phone'], $_POST['message'])) {
        
        // Sanitize and validate inputs
        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));

        // Validate that all required fields are filled
        if ($email && !empty($name) && !empty($subject) && !empty($message) && !empty($phone)) {
            // Prepare SQL query to insert data into the table
            $query = "INSERT INTO contact_messages (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("sssss", $name, $email, $subject, $phone, $message); // Bind parameters
                $stmt->execute(); // Execute the query
                $stmt->close(); // Close the statement

                // Display success alert using SweetAlert
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: 'Thank you for getting in touch. We have received your message.',
                        confirmButtonColor: '#FFA500',
                        iconColor: '#FFA500'
                    }).then(() => {
                        window.location = 'index.php'; // Redirect to homepage or another page
                    });
                </script>";
            } else {
                // Display error alert if query fails
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Failed to send message. Please try again later.',
                        confirmButtonColor: '#FFA500',
                        iconColor: '#FFA500'
                    });
                </script>";
            }
        } else {
            // Display validation alert if inputs are incomplete or invalid
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: 'Please fill in all fields correctly.',
                    confirmButtonColor: '#FFA500',
                    iconColor: '#FFA500'
                });
            </script>";
        }
    }
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>