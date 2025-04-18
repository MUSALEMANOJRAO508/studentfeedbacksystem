<?php
include('api/connect.php');

$sql = "SELECT s_no, sub_name FROM subjects";
$result = mysqli_query($ofsConnection, $sql);

$subjects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $subjects[] = $row;
}

header('Content-Type: application/json');
echo json_encode($subjects);
?>
