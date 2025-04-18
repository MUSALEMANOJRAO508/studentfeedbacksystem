<?php
    include('connect.php');
        $stdCount = "SELECT COUNT(*) as count FROM tab10";
        $result = mysqli_query($reportConnection,$stdCount);
        if($result)
        {
            $counter = mysqli_fetch_assoc($result);
        } 
        echo json_encode($counter);
?>