<?php include('dbConn.php'); 
include('aShopCart.php');
//Include Connection to database.
?>

<html lang="en">
<head>
<link rel="stylesheet" href="css/stylesheet.css"> 
    <title>Program For Task 1 - 19014225</title>
</head>

<body>
    <div class="login">
        <h1>Login Page </h1>
    <form action = "index.php" method = "post" >
        FirstName:
        <input type = "text" name = "firstname" >
        LastName:
        <input type = "text" name = "lastname" >
        Email:
        <input type = "text" name = "email" >
        Password
        <input type = "text" name = "passwrd" >

        <input type="submit" name = "btnLogin" value = "Login">
        <input type="submit" name = "btnRegister" value = "Register">
    </form>

</div>

</body>

</html>

<?php

if (isset($_POST['btnLogin']))
{
    //Create new variables and store accordingly.
    $FirstName = $_POST['firstname'];
    $LastName = $_POST['lastname'];
    $Email = $_POST['email'];
    $UserPass = $_POST['passwrd'];
    $md5Hash = md5($UserPass);


    //Validate that all fields have text
    if($FirstName != '' && $LastName != '' && $Email != '' && $UserPass != '' )
    {
       //Query to fetch data
        $result = ("SELECT * FROM tbl_User WHERE FirstName = '$FirstName' AND LastName = '$LastName' AND Email = '$Email' AND UserPassword = '$md5Hash'");
        $res=mysqli_query($conn,$result);
        if (mysqli_num_rows($res) > 0) 
        {
            echo '<script>alert("Login Succesfull")</script>';
            echo '<script>window.location="myShop.php"</script>';
            //header("Location: myShop.php?");
            $userToken = 1;
        }
        else if($FirstName != '' && $LastName != '' && $Email != '' && $UserPass != '' )
        {
            $resultAdmin = ("SELECT * FROM tbl_admin WHERE FirstName = '$FirstName' AND LastName = '$LastName' AND Email = '$Email' AND UserPassword = '$UserPass'");
            $res2=mysqli_query($conn,$resultAdmin);
            {
                if (mysqli_num_rows($res2) > 0) 
                {
                    echo '<script>alert("Welcome Admin!")</script>';
                    header("Location: myShopAdmin.php?");
                }
                else
                {
                    echo '<script>alert("Incorrect Credentials!")</script>';
                }
                
            }
        }
    }
    else {
        echo '<script>alert("Please fill in all fields")</script>';   
    }
} 
if (isset($_POST['btnRegister']))
{
    //Create new variables and store accordingly.
    $FirstName = $_POST['firstname'];
    $LastName = $_POST['lastname'];
    $Email = $_POST['email'];
    $UserPass = md5($_POST['passwrd']);


    if($FirstName != 0 || $FirstName != '' || $FirstName != null || $FirstName != "" && $UserPass != 0 || $UserPass != '' || $UserPass != null || $UserPass != "")
    {
       
        $sqlRegister = "INSERT INTO tbl_user (FirstName, LastName, Email, UserPassword) VALUES ('$FirstName','$LastName','$Email','$UserPass')";

        if ($conn ->query($sqlRegister) === TRUE)
        {
           echo '<script>alert("Registered Successful")</script>'; 
        }
        else {
         echo "ERROR: " .$sql. "<br>" . $conn->error;
        }
    }
    else {
        echo '<script>alert("Please fill in all fields")</script>';   
    }

} 

?>
