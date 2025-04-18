<?php
    // Include database connection file
    include('connect.php');
    @session_start();

    // Get total number of tables in database
    $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'report';";
    $total_tables = mysqli_query($informationConnection, $sql);
    $count = mysqli_fetch_array($total_tables);

    // Get total number of questions
    $sql_q = "SELECT COUNT(*) AS question_count FROM question";
    $result_q = mysqli_query($ofsConnection, $sql_q);
    $row_q = mysqli_fetch_assoc($result_q);
    $question_count = $row_q['question_count'] ?? 0; // Handle null cases

    // Get current number of students polled
    $sqlcurrentc = "SELECT COUNT(*) AS total_students FROM tab1";
    $sqlcurrentf = mysqli_query($reportConnection, $sqlcurrentc);
    $row = mysqli_fetch_assoc($sqlcurrentf);
    $sqlcurrent = (int) ($row['total_students'] ?? 0); // Handle null cases

    // Calculate total percentage denominator
    $total_topercentage = ($sqlcurrent * 5 * $question_count);
    
    $all_percentages = [];

    // Avoid division by zero
    if ($total_topercentage > 0) {
        for ($i = 1; $i <= $count[0]; $i++) {
            $table_name = "tab" . $i;
            $temp_total = 0;

            for ($j = 1; $j <= $question_count; $j++) {
                $sql_sum = "SELECT SUM(CAST(`q$j` AS SIGNED)) AS total FROM `$table_name`";
                $result_sum = mysqli_query($reportConnection, $sql_sum);
                $row_sum = mysqli_fetch_assoc($result_sum);
                $temp_total += (int) ($row_sum['total'] ?? 0); // Handle null cases
            }

            // Calculate percentage safely
            $percentages = ($temp_total / $total_topercentage) * 100;
            $all_percentages[] = $percentages;
        }
    } else {
        // If total_topercentage is zero, return 0% for all tables
        for ($i = 1; $i <= $count[0]; $i++) {
            $all_percentages[] = 0;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($all_percentages);
?>
