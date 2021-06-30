<html lang="en">
<head>
    <title>Reading File Data!</title>
</head>

<body>
<div class="login">
    <h1>UploadData!</h1>
    <form action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
        <input type="submit" name = "btnShow" value = "Send Data To Users Table!">
    </form>

</div>

</body>

</html>
<?php
include ('dbConn.php');
$myfile = new SplFileObject('userData.txt');
if(isset($_POST['btnShow']))
{
    while (!$myfile->eof())
    {
        $line = $myfile ->fgets();
        list ($name,$surn,$email,$pass) = explode (',',$line);
        //$hashPass = md5($pass);
        $sql = "INSERT INTO tbl_User(FirstName, LastName, Email, UserPassword)
        VALUES ('$name','$surn','$email','$pass')";
        
        if ($conn -> query($sql) === TRUE)
        {
            echo "Added.";
        }
        else{
            echo "Error: ".$sql."<br>".$conn->error;
        }
        
      
    }
    
}

?>