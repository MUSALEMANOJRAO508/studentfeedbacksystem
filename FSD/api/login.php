<?php
include('connect.php');
session_start();
// Function to handle student login
function handleStudentLogin($admission, $dob, $sdbConnection) {
    $query = "SELECT * FROM sdetails WHERE admission = ? AND dob = ?";
    $stmt = mysqli_prepare($sdbConnection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $admission, $dob);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            // Set cookies for 30 days to store login details
            setcookie("student_admission", $admission, time() + (86400 * 30), "/");
            setcookie("student_dob", $dob, time() + (86400 * 30), "/");
            $_SESSION['admission'] = $admission;
            echo "
            <script>
                window.location='../StudentFeedback.html';
            </script>";
        } else {
            echo "
            <script>
                alert('Invalid Admission Number or Date of Birth');
                window.location='../login.html';
            </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing student query: " . mysqli_error($sdbConnection));
    }
}

// Function to handle faculty login
function handleFacultyLogin($userId, $password, $ofsConnection) {
    // Check if the cookies for faculty exist
    if (isset($_COOKIE['faculty_userid']) && isset($_COOKIE['faculty_password'])) {
        if ($_COOKIE['faculty_userid'] !== $userId || $_COOKIE['faculty_password'] !== $password) {
            // Credentials do not match, restrict login
            echo "
            <script>
                alert('already one a person login and work is in progress wait for a while');
                window.location='../login.html';
            </script>";
            exit();
        }
    }

    $query = "SELECT * FROM registration WHERE user_name = ? AND password = ?";
    $stmt = mysqli_prepare($ofsConnection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $userId, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Set cookies for 2 days to store login details
            setcookie("faculty_userid", $userId, time() + (86400 * 2), "/");
            setcookie("faculty_password", $password, time() + (86400 * 2), "/");

            echo "
            <script>
                window.location='../FacultyRegistration.html';
            </script>";
        } else {
            echo "
            <script>
                alert('Invalid User ID or Password');
                window.location='../login.html';
            </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing faculty query: " . mysqli_error($ofsConnection));
    }
}

// Main Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];

    if ($role === 'student') {
        $admission = $_POST['admission'];
        $dob = $_POST['dob'];
        handleStudentLogin($admission, $dob, $sdbConnection);
    } elseif ($role === 'faculty') {
        $userId = $_POST['userid'];
        $password = $_POST['password'];
        $_SESSION['user_name'] = $userId;
        handleFacultyLogin($userId, $password, $ofsConnection);
    } else {
        echo "
        <script>
            alert('Invalid Role Selected');
            window.location='../login.html';
        </script>";
    }
}
?>
