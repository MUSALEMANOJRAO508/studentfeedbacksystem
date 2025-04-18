<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

session_start(); // Start a new session
$_SESSION['user_name'] = ""; // Set user_name to an empty string
header("Location: ../login.html");
exit();
?>
