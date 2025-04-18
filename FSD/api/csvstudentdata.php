<?php
include('connect.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['submit']))
    {
        //validating
        if(isset($_FILES['file']))
        {
            //get file name
            $filename = $_FILES['file']['name'];
            $filelocation = $_FILES['file']['tmp_name'];
            $fileExtension = pathinfo($filename , PATHINFO_EXTENSION);
            if($fileExtension == 'csv')
            {
                $handle = fopen($filelocation ,'r');
                if($handle !==FALSE){
                    fgetcsv($handle);
                    while(($data = fgetcsv($handle ,1000 ,",")) !== FALSE)
                    {
                        $pin = $data[0];
                        $dob = $data[1];
                        $regulation = $data[2];
                        $yojoin = $data[3];

                        $sql = "INSERT INTO sdetails (admission, dob, regulation ,yojoin)VALUES('$pin' ,'$dob' ,'$regulation' ,'$yojoin')";
                        if(!mysqli_query($sdbConnection ,$sql))
                        {
                            echo"
                            <script>
                            alert('failed to insert');
                            </script>";
                        }
                    }
                    echo"
                    <script>
                    alert('data inserted successfully');
                    window.location = '../studentdata.html';
                    </script>";
                }
                else
                {
                    echo"
                    <script>
                    alert('problem in opening file');
                    </script>";
                }
                fclose($handle);
            }
            else{
                echo"
                <script>
                    alert('invalid extension');
                </script>";
            }
        }else{
            echo"
            <script>
            alert('file is not loading');
            </script>";
        }

    }else{
        echo"
        <script>
            alert('invalid file upload');
        </script>";
    }
}else{
    echo"
    <script>
        alert('invalid request');
    </script>";
}
?>