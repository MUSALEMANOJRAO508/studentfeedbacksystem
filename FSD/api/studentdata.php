<?php
include('connect.php'); 

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate & sanitize input
    $pin = isset($_POST['pin']) ? trim($_POST['pin']) : null;
    $dob = isset($_POST['dob']) ? trim($_POST['dob']) : null;
    $reg = isset($_POST['reg']) ? trim($_POST['reg']) : null;

    // Check for empty values
    if (empty($pin) || empty($dob) || empty($reg)) {
        echo "
        <script>
            alert('Error: PIN, Date of Birth, and Regulation are required.');
            window.history.back();
        </script>";
        exit();
    }

    
    $yojoin = "20" . substr($pin, 0, 2);

    // Check if the PIN already exists (to avoid duplicate primary key errors)
    $check_sql = "SELECT admission FROM sdetails WHERE admission = ?";
    $check_stmt = mysqli_prepare($sdbConnection, $check_sql);

    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $pin);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "
            <script>
                alert('Error: Admission PIN already exists. Please use a different one.');
                window.history.back();
            </script>";
            mysqli_stmt_close($check_stmt);
            exit();
        }

        mysqli_stmt_close($check_stmt);
    } else {
        echo "Error preparing check statement: " . mysqli_error($sdbConnection);
        exit();
    }

    // Prepare the SQL statement to insert the data
    $sql = "INSERT INTO sdetails (admission, dob, regulation, yojoin) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($sdbConnection, $sql);

    if ($stmt) {
        // Bind parameters (assuming all are strings)
        mysqli_stmt_bind_param($stmt, "ssss", $pin, $dob, $reg, $yojoin);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "
            <script>
                window.location='../studentdata.html';
                </script>";
        } else {
            echo "Error inserting record: " . mysqli_error($sdbConnection);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing insert statement: " . mysqli_error($sdbConnection);
    }

    // Close database connection
    mysqli_close($sdbConnection);
} 
?>
