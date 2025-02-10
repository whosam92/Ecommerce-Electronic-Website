<?php
session_start();
session_destroy(); // Destroy the session
header('Location: index-4.php'); // Redirect to homepage
exit();
