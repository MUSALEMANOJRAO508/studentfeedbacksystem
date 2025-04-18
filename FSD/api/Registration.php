<?php
include('connect.php');

$name = $_POST["userid"];
$password = $_POST["password"];
$role = $_POST["role"];
$sql = "SELECT COUNT(*) FROM registration where user_name ='$name'";
$result = mysqli_query($ofsConnection,$sql);
$row = mysqli_fetch_array($result);
if($row[0]>0)
{
    echo"
    <script>
    alert('already user id is exist try another');
    window.location='../index.html';
    </script>";
}
else if(strlen(trim($password)) > 5)
{
    $insert = mysqli_query($ofsConnection, "INSERT INTO registration (user_name,password,role) VALUES ('$name','$password','$role')");
    if($insert)
    {
        echo "
        <script>
        alert('Successfully inserted.');
        window.location='../login.html';
        </script>";
       
    }
    else
    {
        echo "
        <script>
        alert('Oops! There was an issue.');
        window.location='../Registration.html';
        </script>";
    } 
}

else
{

        echo"
        <script>
        alert('password lessthan 5 charcetr');
        window.location='../Registration.html';
        </script>
        ";

}

?>
 