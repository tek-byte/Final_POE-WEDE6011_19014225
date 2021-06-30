<?php
include('dbConn.php'); 
include('aShopCart.php');

if (!isset($_SESSION)){
    session_start();
    main();
    shopping();
}

if(isset($_POST['deleteItem']))
{
    $sqldlt = "DELETE  FROM tbl_items WHERE ItemID = '$_GET[ItemID]'";
    if ($conn -> query($sqldlt) === TRUE)
    {
        echo '<script>alert("Item Removed By Admin")</script>';
        echo '<script>window.location="myShopAdmin.php"</script>';
    }
    else
    {
        echo "Coult not be deleted";
    }
}

if(isset($_POST['editConfirm']))
{
    
    $sqledt = "UPDATE tbl_items SET Item_Name = '$_POST[edtitemName]', Item_Description = '$_POST[edtitemDescription]', Item_Price = '$_POST[edtitemPrice]', Item_Stock = '$_POST[edtitemStock]' WHERE ItemID = '$_POST[hidden_id]'";
    if ($conn -> query($sqledt) === TRUE)
    {   
        
        echo '<script>alert("Item Edited Successfully")</script>';
        echo '<script>window.location="myShopAdmin.php"</script>';
        
    }
    else
    {
        echo "Coult not be edited.";
    }
                              
    //DO NOT DELETE - UPDATE tbl_items SET `ItemID` = 'it564' WHERE `ItemID` = 'it888'   echo "Yup";
    //UPDATE tbl_items SET `Item_Name` = 'Green', `Item_Description` = 'okajsl', `Item_Price` = '9', `Item_Stock` = '9' WHERE ItemID = 'it564';
}

else
{
  
}

if(isset($_POST['itemAddAdmin']))
{
            
    $sqladd = "INSERT INTO tbl_items (ItemID, Item_Name, Item_Description, Item_Price, Item_Stock) VALUES ('$_POST[itemID]','$_POST[itemName]','$_POST[itemDescription]','$_POST[itemPrice]','$_POST[itemStock]')";
    if ($conn -> query($sqladd) === TRUE)
    {
        echo '<script>alert("New Item Added By Admin")</script>';
        echo '<script>window.location="myShopAdmin.php"</script>';
    }
    else
    {
        echo $_POST['itemID'];
    }
}


if(isset($_GET['action']))
{
    removeItem();
    //shopCart();
}


if(isset($_GET['action']))
{
    emptyShoppingCart();
}
if (isset($_POST['shopCart']) || isset($_POST['shopCartMain']) )
{
    shopCart();
}

if (isset($_POST['shopItems']))
{
    shopping();
}

if (isset($_POST['admin']))
{
    ?>
    <div align = "center">
    <form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3 style = "color :white;">Admin UserName</h3>
            <input type = "text" name = "admUser" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
            <h3 style = "color :white;">Admin Password</h3>
            <input type = "text" name = "admPass" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
            <input type = "submit" name = "admLogin" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Login"> 
    </form>
    <div>
    <?php
    
}
if(isset($_POST['admLogin']))
{
    session_destroy();
    header("Location: myShopAdmin.php");
}


function main()
{
    ?>
    <style>
        body{
            overflow:scroll;
        }

        main{
            overflow:scroll;
        }
    
        table,th,td{
            font-family: 'Agency FB Bold', arial;
            text-shadow: 4px 4px 4px #aaa;
            border: 0px solid black;
            border-collapse:collapse;
        }

        th,td{
            background-color: #f1f1c1;
            font-family: 'Agency FB Bold', arial;
            margin: -150px 0 0 -150px;
            align :center;
            color :black;
            padding: 25px;
        }

        .wrapper{
            display:grid;
            grid-template-columns:repeat(5, 1fr);
            grid-gap:2em;
            grid-row: inherit;
            grid-auto-rows: minmax(100px, auto);
            padding:8em;
        }

        .wrapper > div{
            align:center;
            background-image: url('Images/Banner.jpg');
            padding:1.5em;
        }

        .wrapper > div:nth-child(odd){
            background-image: url('Images/Banner.jpg');
        }

        .nested{
            display:grid;
            align:right;
        }

        .head{
            display:grid;
            grid-template-columns:repeat(1, 1fr);
            grid-gap:1em;
            grid-row: inherit;
            grid-auto-rows: minmax(10px, auto);
            padding:1em;
            background-color: transparent;
            
        }

        .cart{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            grid-gap:2em;
            grid-row: inherit;
            grid-auto-rows: minmax(5px, auto);
            padding-left:5em;
            padding-right:5em;
            
        }

        .cartshop{
            grid-gap:2em;
            grid-row: inherit;
            grid-auto-rows: minmax(5px, auto);
            padding-left:5em;
            padding-right:5em;
            background-color: #a8ecff;
        }

        table {
            width: 100%;
            background-color:transparent;
            border: 12px;
            min-width: 100%;
            position: relative;
            opacity: 0.99;
            background:transparent;
        }
    
</style>
<?php
}
?>
<html>
	<head>
	    <title>Dream Screens</title>
        <link rel="stylesheet" href="css/stylesheet.css">
        <link href="http://allfont.net/allfont.css?fonts=agency-fb-bold" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	</head>

    <body class = "nested">
            
    </body>
</html>
<?php

if (isset($_POST['login']))
{
    echo 'Items.';
    echo '<script>window.location="index.php?"</script>';
}

if (isset($_POST['contShop']))
{
    shopping();
}

//https://www.youtube.com/watch?v=GUcN9xRpO7U&ab_channel=eInstructor

function shopping()
    {   
        ?>
            <header class = "head">
            <table class = 'table'>
                <form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <tr>
                    <tr>
                        <td width = "85%" background = "Images/Banner.jpg">
                            <div class = "nested" style = "font-size: 30px; color: white; font-style: oblique;"><h1>Admin Use.<br></h1></div>
                        </td>
                        <td align = "right" width = "15%" style = "background:transparent">
                        <?php
                            ?>
                            <br>
                            <input type = "submit" name ="admLogout" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Logout"><br>
                            <input type = "submit" name ="admAdd" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Add Item">
                           
                            <?php
                                if(isset($_POST['admLogout']))
                                {
                                    session_destroy();
                                    header("Location: index.php");
                                }
                                if(isset($_POST['admAdd']))
                                {
                                    
                                    ?>
                                    <div align = "center">
                                    <form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <h3 style = "color :white;">Item ID</h3>
                                            <input type = "text" name = "itemID" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>

                                            <h3 style = "color :white;">Item Name</h3>
                                            <input type = "text" name = "itemName" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>

                                            <h3 style = "color :white;">Item Description</h3>
                                            <input type = "text" name = "itemDescription" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>

                                            <h3 style = "color :white;">Item Price</h3>
                                            <input type = "text" name = "itemPrice" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>

                                            <h3 style = "color :white;">Item Stock</h3>
                                            <input type = "text" name = "itemStock" class = "form-control" value="" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
                                            
                                            <input type = "submit" name = "itemAddAdmin" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Add Item"> 
                                    </form>
                                    <div>
                                    <?php
                                
                                }
                                if(isset($_POST['editItem']))
                                { 
                                include('dbConn.php');
                                //For Admin use only. Edit information.
                                $sqlEdit = "SELECT * FROM tbl_items WHERE ItemID = '$_GET[ItemID]'";
                                $result5 = $conn->query($sqlEdit);

                                    if($result5 ->num_rows > 0)
                                    {
                                        //If True, set variable to contain new stock amount for item by it's Id.
                                        while($row5 = $result5->fetch_assoc()) 
                                        {
                                            ?>
                                            <div align = "center">
                                            <img src = "Images/<?php echo $_GET['ItemID']?>.jpg" alt="" style = "width: 350px; height: 300px;">
                                            <form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <h2 style = "color :white;">Item : <?php echo $_GET['ItemID']; ?></h2>

                                            <h3 style = "color :white;">New Name:</h3>
                                                <input type = "text" name = "edtitemName" class = "form-control" value="<?php echo $row5['Item_Name']; ?>" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
                                            <h3 style = "color :white;">New Description:</h3>
                                                <input type = "text" name = "edtitemDescription" class = "form-control" value="<?php echo $row5['Item_Description']; ?>" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
                                            <h3 style = "color :white;">New Price:</h3>
                                                <input type = "text" name = "edtitemPrice" class = "form-control" value="<?php echo $row5['Item_Price']; ?>" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
                                            <h3 style = "color :white;">New Stock:</h3>
                                                <input type = "text" name = "edtitemStock" class = "form-control" value="<?php echo $row5['Item_Stock']; ?>" style = "width: 500px; height: 50px; margin-bottom: 25px;"/>
                                                                                                        
                                                <input type = "submit" name = "editConfirm" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Confirm"> 
                                                <input type = "hidden" name = "hidden_id" value="<?php echo $_GET['ItemID'];?>"/>
                                            </form>
                                            <div>
                                            <?php    
                                        }
                                    }
                                    else
                                    {
                                        echo "no rows found";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    </tr>
                </form>
            </table>
            
            </header>
        <?php
        include('dbConn.php');
        ?>
            <div class ='wrapper' >
                <?php
                $sql = "SELECT * FROM tbl_items";
                $result = $conn->query($sql);


                    if ($result->num_rows > 0)
                    {
                    // output data of each row
                        while($row = $result->fetch_assoc()) 
                        {   
                            ?>
                            <div class = "nested ">
                            <form method = "post" action="myShopAdmin.php?action=&ItemID=<?php echo $row['ItemID'];?>">
                                <img src="Images/<?php echo $row['ItemID'];?>.jpg" class="img-responsive" /><br>
                                <h3 class = "text-info"><?php echo $row['ItemID'];?></h3>
                                <h3 class = "text-info"><?php echo $row['Item_Name'];?></h3>
                                <h4 class = "text-danger">Description:<br><br> <?php echo $row['Item_Description'];?></h4><br>
                                <h4 class = "text-Info"><br>Product Price:</h4><h4 class = "text-danger"><br>R <?php echo $row['Item_Price'];?></h4><br>
                                <h4 class = "text-info"><br>Total Available: <?php echo $row['Item_Stock'];?></h4>
                                <input type = "submit" name = "editItem" class="btn btn-success" value="Edit" style= align:center/><br><br>
                                <input type = "submit" name = "deleteItem" class="btn btn-success" value="Delete" style= "align:center background-color:red"/>
                               
                            </form>
                            </div>
                            
                        <?php   
                        }   
                    

                        
                    }
                    
                ?>
                </div>

        <?php
    }

?>