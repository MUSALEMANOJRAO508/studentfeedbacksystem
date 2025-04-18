<?php
// Connect to the 'ofs' database
$ofsConnection = mysqli_connect("localhost", "root", "", "ofs");
if (!$ofsConnection) {
    die("Connection to 'ofs' database failed: " . mysqli_connect_error());
}

// Connect to the 'sdb' database
$sdbConnection = mysqli_connect("localhost", "root", "", "sdb");
if (!$sdbConnection) {
    die("Connection to 'sdb' database failed: " . mysqli_connect_error());
}
//connec to thr 'report' database
$reportConnection = mysqli_connect("localhost", "root", "", "report");
if (!$reportConnection) {
    die("Connect to 'report' database failed: ". mysqli_connect_error());
}
$informationConnection = mysqli_connect("localhost","root","","information_schema");
if(!$informationConnection){
    die("Connection to 'information_schema' database failed: ". mysqli_connect_error());
}
?>