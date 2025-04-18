<?php
session_start();

// Check if a faculty member is logged in
$response = array(
    "faculty_logged_in" => isset($_SESSION['faculty_logged_in']) && $_SESSION['faculty_logged_in'] === true
);

// Allow a logged-out user to log in again by checking if the session is closed
if (!$response["faculty_logged_in"]) {
    // If no one is logged in, reset the session (clear any old sessions)
    session_unset();
    session_destroy();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
