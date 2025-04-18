<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear the Question table
    $delete_sql = "DELETE FROM question";
    if (!mysqli_query($ofsConnection, $delete_sql)) {
        die("Error deleting previous questions: " . mysqli_error($ofsConnection));
    }

    // Get the number of questions safely
    $QCount = isset($_POST['QCount']) ? (int)$_POST['QCount'] : 0;
    //globally uploading 
    $_SESSION['QCount'] = $QCount;
    // Prepare insert statement
    $insert_sql = "INSERT INTO question (s_no, Question) VALUES (?, ?)";
    $stmt = mysqli_prepare($ofsConnection, $insert_sql);

    if ($stmt) {
        for ($i = 1; $i <= $QCount; $i++) {
            $questionKey = "question" . $i;
            $question = isset($_POST[$questionKey]) ? htmlspecialchars($_POST[$questionKey], ENT_QUOTES, 'UTF-8') : null;

            if (!empty($question)) {
                mysqli_stmt_bind_param($stmt, "is", $i, $question);
                if (!mysqli_stmt_execute($stmt)) {
                    die("Error inserting question $i: " . mysqli_stmt_error($stmt));
                }
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);
        echo "<script>alert('Data submitted successfully!'); window.location = '../FacultyRegistration.html';</script>";
    } else {
        die("Error preparing statement: " . mysqli_error($ofsConnection));
    }

    // Close database connection
    mysqli_close($ofsConnection);
} else {
    echo "<script>alert('Invalid request!'); window.location = '../FacultyRegistration.html';</script>";
}
?>
