<?php
header("Content-Type: application/json"); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "api/connect.php"; // Ensure the database connection file is correct

if (!isset($_GET['subject_id']) || empty($_GET['subject_id'])) {
    echo json_encode(["error" => "Subject ID is required"]);
    exit;
}

$subject_id = intval($_GET['subject_id']); // Sanitize subject_id input

// Check if the database connection is established
if (!$ofsConnection) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fetch the same set of feedback questions for any subject
$sql = "SELECT s_no, question FROM question"; // Assuming table name is "feedback_questions"
$result = $ofsConnection->query($sql);

$questions = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            "s_no" => $row["s_no"], 
            "Question" => $row["question"]
        ];
    }
}

$ofsConnection->close();

// Return the same set of feedback questions for all subjects
echo json_encode(["success" => true, "questions" => $questions]);
?>
