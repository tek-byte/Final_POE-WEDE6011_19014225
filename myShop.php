<?php
include('dbConn.php'); 
include('aShopCart.php');

if (!isset($_SESSION)){
    session_start();
    main();
}

if(isset($_POST['add_to_cart']))
{

    if($_POST['quantity'] > $_POST['hidden_quantity'])
    {
        unset($_SESSION['cart'][$keys]);
        echo '<script>alert("Quantity Exceeded")</script>';
        //echo '<script>window.location="myShop.php?"</script>';
        shopping();
    }
    else{
        addToCart();
        shopping();
    }
    
}

if(isset($_GET['action']))
{
    removeItem();
    
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

if (isset($_POST['payNow']))
{          
    if(!empty($_SESSION['cart']))
    {
        $total = 0;
        foreach($_SESSION['cart'] as $keys => $values)
        {
            $ord = "ord";
            $det = "det";
            $num = 0;

            $count = count($_SESSION['cart']);

            $checkId = $values['Item_ID'];
            $checkName = $values['Item_Name'];
            $checkDescription = $values['Item_Description'];
            $checkPrice = $values['Item_Price'];
            $checkQuan = $values['Item_quantity'];
            $total = $total + ($values['Item_quantity'] * $values['Item_Price']);

            $ref_number = $ord.$num.$checkQuan.$checkId.$count;
            $detID = $det.$ord.$count;
            $ordID = $ord.$num.$count.$checkQuan;
    
            ///////____Step 2: Write order to Database
            $sqlpay = "INSERT INTO tbl_orde (ItemID, OrderID, Quantity, RefNumber, Amount) VALUES ('$checkId','$ordID','$checkQuan','$ref_number','$checkPrice')";
            if($conn -> query($sqlpay) === TRUE)
            {
                ///__Find Stock number by item id.
                $sqlQuanEdit = "SELECT * FROM tbl_items WHERE ItemID = '$checkId'";
                $result3 = $conn->query($sqlQuanEdit);

                if($result3->num_rows > 0)
                {
                    //If True, set variable to contain new stock amount for item by it's Id.
                    while($row3 = $result3->fetch_assoc()) 
                    {
                        //New stock number for that Item.
                        $newStock = $row3['Item_Stock'];
                        $stockNow = $newStock - $checkQuan;
                    }
                }
                else
                {
                    echo "no rows found";
                }
                
                //echo '<script>alert("Order Placed")</script>';
                //echo '<script>window.location="myShopAdmin.php"</script>';
            }
            else
            {
                echo "Could Not Write. - Check sql statement, or if table exists.";
            }

            ///////____Step 3 alter stock.
            $sqlalter = "UPDATE tbl_items SET Item_Stock = '$stockNow' WHERE ItemID = '$checkId'";
            if ($conn -> query($sqlalter) === TRUE)
            {   
                if($newStock == 0 || $newStock == "Out of Order")
                {
                    $sqlDeplete = "UPDATE tbl_items SET Item_Stock = 'Out of order' WHERE Item_Stock = '$newStock'";
                    if ($conn -> query($sqlDeplete) === TRUE)
                    {   
                        //echo $newStock;
                        echo '<script>alert("Item Out Of Order")</script>';
                        echo '<script>window.location="myShop.php"</script>';
                    }
                    else
                    {
                        echo "Stock not yet";
                    }
                }
                else
                {
                    echo '<script>alert("Purchase Successful")</script>';
                    unset($_SESSION['cart']);
                    ?>  
                    <div class = "cart" style = "font-size: 30px; color: white; font-style: oblique" align = "center">
                        
                        <table class = "table table-bordered" style="border:3px solid black" >
                        <th align = center  background = "Images/delivery.jpg" width="350" height="250" style = "padding:25px;"><h1><br>Products Purchased</h1></th>
                        </table>
                        <div class = "nested">
                            <form class = "nested" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type = "submit" name = "contShop"  value="Continue Shopping" style = "width: 400px; height: 75px; margin-bottom: 25px; color :white;"/>
                                <input type  = "submit" name = "checkout"  value="Checkout! ☻" style = "width: 400px; height: 75px; margin-bottom: 25px; color :white;"/>  
                            </form>
                        
                        </div>
                    </div>
            
                <?php
                }
                
            }
            else
            {
                echo "Could not update new table";
            } 
            
        }
            ?>
            <?php
    }
    
}


if (isset($_POST['admin']))
{
    admin();
}


if(isset($_POST['admLogin']))
{
    //Create new variables and store accordingly.
    $FirstName = $_POST['admUser'];
    $UserPass = $_POST['admPass'];

    //Validate that all fields have text
    if($FirstName != '' && $UserPass != '' )
    {
       //Query to fetch data
        $adminLog = ("SELECT * FROM tbl_admin WHERE FirstName = '$FirstName' AND UserPassword = '$UserPass'");
        $res6=mysqli_query($conn,$adminLog);
        if (mysqli_num_rows($res6) > 0) 
        {
            echo '<script>alert("Login Succesfull")</script>';
            echo '<script>window.location="myShopAdmin.php?"</script>';
        }
        else
        {
            echo '<script>alert("Incorrect Credentials!")</script>';
            admin();
        }
    }
    else 
    {
        echo '<script>alert("Please fill in all fields")</script>';   
        admin();
    }
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
    
    <html>
	<head>
	    <title>Dream Screens</title>
        <link rel="stylesheet" href="css/stylesheet.css">
        <link href="http://allfont.net/allfont.css?fonts=agency-fb-bold" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	</head>

    <body class = "nested">
            <header class = "head">
            <table class = 'table'>
                <form method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <tr>
                    <tr>
                        <td width = "85%" background = "Images/Banner.jpg">
                            <div class = "nested" style = "font-size: 30px; color: white; font-style: oblique;"><h1>Welcome!<br>Find the screen of your dreams!</h1></div>
                        </td>
                        <td align = "right" width = "15%" style = "background:transparent">
                        <?php
                            if(!isset($_SESSION))
                            {
                                echo '<script>window.location="index.php?"</script>';
                            }
                            else
                            {
                                ?>
                                <input type = "submit" name ="login" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Login"> 
                                <?php
                                
                            }
                            ?>
                            <br>
                            <input type = "submit" name ="admin" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Admin"><br>
                            <input type = "submit" name ="shopCart" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Show Cart">
                            <input type = "submit" name ="shopItems" style = "width: 170px; height: 50px; margin-bottom: 25px; color :white;" value = "Shop Items!"> 
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    </tr>
                </form>
            </table>
            
            </header>

    </body>
</html>
<?php
}

if (isset($_POST['login']))
{
    echo 'Items.';
    echo '<script>window.location="index.php?"</script>';
}

if (isset($_POST['contShop']))
{
    shopping();
}
if (isset($_POST['checkout']))
{
    checkout();
}


//https://www.youtube.com/watch?v=GUcN9xRpO7U&ab_channel=eInstructor

function shopping()
    {   
        include('dbConn.php');
        ?>
            <div class ='wrapper' >
                <?php
                $sql = "SELECT * FROM tbl_items WHERE Item_Stock != 'Out of Order'";
                $result = $conn->query($sql);


                    if ($result->num_rows > 0)
                    {
                    // output data of each row
                        while($row = $result->fetch_assoc()) 
                        {   
                            ?>
                            <div class = "nested ">
                            <form method = "post" action="myShop.php?action=add&ItemID=<?php echo $row['ItemID'];?>">
                                <img src="Images/<?php echo $row['ItemID'];?>.jpg" class="img-responsive" /><br>
                                <h3 class = "text-info"><?php echo $row['Item_Name'];?></h3>
                                <h4 class = "text-danger">Description:<br><br> <?php echo $row['Item_Description'];?></h4><br>
                                <h4 class = "text-Info"><br>Product Price:</h4><h4 class = "text-danger"><br>R <?php echo $row['Item_Price'];?></h4><br>
                                <h4 class = "text-info"><br>Total Available: <?php echo $row['Item_Stock'];?></h4>
                                <input type = "text" name = "quantity" class = "form-control" value="1" style = "width: 75px; height: 50px; margin-bottom: 25px;"/>
                                <input type = "hidden" name = "hidden_name" value="<?php echo $row['Item_Name'];?>"/>
                                <input type = "hidden" name = "hidden_desc" value="<?php echo $row['Item_Description'];?>"/>
                                <input type = "hidden" name = "hidden_price" value="<?php echo $row['Item_Price'];?>"/>
                                <input type = "hidden" name = "hidden_quantity" value="<?php echo $row['Item_Stock'];?>"/>
                                <input type = "submit" name = "add_to_cart" class="btn btn-success" value="Add to Cart" style= align:center/>
                            </form>
                            </div>
                        <?php   
                        }   
                    
                        
                    }
                ?>
                </div>
                <div class = "nested">
                <form class = "wrapper" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type = "submit" name = "shopCartMain"  value="View Shopping Cart" style = "font-size:25px; width: 350px; height: 75px; margin-bottom: 0px; color :solid black; background-color: #a8ecff;"/> 
                </form>
            
            </div>
        <?php
    }

    function admin()
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
    
?>