<?php
include('connect.php');

//here are we are going to delete the all the database for new registration
$delete_sql="DELETE FROM sdetails";

mysqli_query($sdbConnection,$delete_sql);

echo"
<script>
  window.location = '../FacultyRegistration.html';
</script>";
?>