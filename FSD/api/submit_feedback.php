<?php
include('connect.php');
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adnumber = isset($_SESSION['admission']) ? $_SESSION['admission'] : null;
    // Validation and sanitizing input
    $subject_id = isset($_POST['subject_id']) ? trim($_POST['subject_id']) : null;
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : [];
    $submit_btn = isset($_POST['submit']) ? trim($_POST['submit']) : null;
    //check user already  submitted or not if submitted then giving alert to him and redired to submit feedback html form 
    $sql = "SELECT * FROM tab$subject_id WHERE id = '$adnumber'";
    $result = mysqli_query($reportConnection, $sql);        
    if (mysqli_num_rows($result) > 0) {
        echo "
        <script>
        alert('You have already submitted feedback for this subject');
        window.location.href = '../StudentFeedback.html';
        </script>";
    }
    $QCount = isset($_SESSION['QCount']) ? (int)$_SESSION['QCount'] : 0;
    
    // Sanitizing values
    $subject_id = mysqli_real_escape_string($reportConnection, $subject_id);
    $adnumber = mysqli_real_escape_string($reportConnection, $adnumber);

    // preparing array
    $values = ["'".$adnumber."'"];

    foreach ($feedback as $feedbacks) {
        if (isset($feedbacks)) {
            // Check if the feedback is numeric and avoid quotes if so
            if (is_numeric($feedbacks)) {
                $values[] = $feedbacks;
            } else {
                $feedbacks = mysqli_real_escape_string($reportConnection, $feedbacks);
                $values[] = "'" . $feedbacks . "'";
            }
        }
    }

    $values_sql = implode(", ", $values);
    $table_name = 'tab' . $subject_id;

    // Generating column names dynamically
    $value = ["id"];
    for ($i = 1; $i <= 10; $i++) {
        $value[] = "q$i";
    }
    $values_q = implode(", ", $value);
    print_r($value);
    // Preparing SQL query
    $sql = "INSERT INTO $table_name ($values_q) VALUES ($values_sql)";

    // Executing the query
    if (!mysqli_query($reportConnection, $sql)) {
        echo "
        <script>
        alert('Failed to insert feedback: " . mysqli_error($reportConnection) . "');
        </script>";
    } else {
        echo "
        <script>
        alert('Feedback submitted successfully for subject: $subject_id');
        window.location.href = '../StudentFeedback.html';
        </script>";
    }
} else {
    echo "
    <script>
    alert('Invalid request');
    </script>";
}
?>
