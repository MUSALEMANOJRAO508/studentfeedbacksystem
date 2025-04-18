<?php
    include('connect.php');
    $stdCount = "SELECT COUNT(*) as count FROM sdetails";
    $result = mysqli_query($sdbConnection,$stdCount);
    if($result)
    {
        $counter = mysqli_fetch_assoc($result);
    }
    echo json_encode($counter);
?>