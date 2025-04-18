<?php
include('connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    // Clearing subjects table before inserting data (Caution: This deletes all previous records)
    $delete_query = "DELETE FROM subjects";
    mysqli_query($ofsConnection, $delete_query);
    // dropping all the tables and new tables are created dynamically while pressing the submit button (Caution : this deletes all previous records)
    for($i = 1;$i <= 10 ;$i++)
    {
        $table_name = "tab" .$i;
        $drop_tab = "DROP TABLE IF EXISTS `$table_name`";
        mysqli_query($reportConnection , $drop_tab);
    }
    // Prepare statement for inserting data
    $insert_data = "INSERT INTO subjects (s_no, sub_name) VALUES (?, ?)";
    $stmt = mysqli_prepare($ofsConnection, $insert_data);
    //fetching the Question count
    $sql_q = "SELECT COUNT(*) AS question_count FROM question";
    $result_q = mysqli_query($ofsConnection , $sql_q);
    $row_q = mysqli_fetch_assoc($result_q);
    $question_count = $row_q['question_count'];
    if ($stmt) {
        // Dynamically inserting subjects
        for ($i = 1; $i <= 10; $i++) 
        {
            $subject_key = "sub" . $i;  // Creating key dynamically
            $subject = isset($_POST[$subject_key]) ? htmlspecialchars($_POST[$subject_key]) : null;

            if (!empty($subject)) 
            {
                mysqli_stmt_bind_param($stmt, "is", $i, $subject);
                mysqli_stmt_execute($stmt);
            }
        }
        //dynamically creating table for each subject
        for ($i = 1; $i <= 10; $i++) 
        {
            $table_name = "tab" . $i; // Dynamic table name
            
            // Construct column definitions dynamically
            $columns = [];
            for ($j = 1; $j <= $question_count; $j++) 
            {
                $columns[] = "`q$j` TEXT NOT NULL";  // q1, q2, ..., qN
            }
            $columns_sql = implode(", ", $columns);
    
            // Define the SQL to create the table
            $sql_c = "CREATE TABLE IF NOT EXISTS `$table_name` (
                id VARCHAR(10) PRIMARY KEY,
                $columns_sql
            )";
    
            // Execute the table creation query
            if (mysqli_query($reportConnection, $sql_c)) 
            {
                echo "Table $table_name created with $question_count columns.<br>";
            } 
            else 
            {
                echo "Error creating table $table_name: " . mysqli_error($ofsConnection) . "<br>";
            }
        }

            // Close the statement
            mysqli_stmt_close($stmt);
            echo "<script>alert('Data submitted successfully!'); window.location = '../FacultyRegistration.html';</script>";
    }
    else 
    {
        echo "<script>alert('Error preparing the statement!'); window.location = '../FacultyRegistration.html';</script>";
    }
} else 
{
    echo "<script>alert('Invalid request!'); window.location = '../FacultyRegistration.html';</script>";
}

?>
