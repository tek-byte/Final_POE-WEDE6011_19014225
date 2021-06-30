<?php
include('dbConn.php'); 
session_start();

if(isset($_POST['add_to_cart']))
{
    if(isset($_SESSION['cart']))
    {
        $item_array_id = array_column($_SESSION['cart'],"ItemID");
        
        if(!in_array($_GET['ItemID'], $item_array_id))
        {
            $count = count($_SESSION['cart']);
            $item_array = array(
                'Item_ID' => $_GET['ItemID'],
                'Item_Name' => $_POST['hidden_name'],
                'Item_Description' => $_POST['hidden_desc'],
                'Item_Price' => $_POST['hidden_price'],
                'Item_quantity' => $_POST['quantity']
            );
            $_SESSION['cart'][$count] = $item_array;
            
        }
        else
        {
            echo '<script>alert("Item Already Added")</script>';
            echo '<script>window.location="HomePage.php"</script>';
        }
    }
    else
    {
        $item_array = array(
            'Item_ID' => $_GET['ItemID'],
                'Item_Name' => $_POST['hidden_name'],
                'Item_Description' => $_POST['hidden_desc'],
                'Item_Price' => $_POST['hidden_price'],
                'Item_quantity' => $_POST['quantity']
        );
        $_SESSION['cart'][0] = $item_array;
    }
}

if(isset($_GET['action']))
{
    if($_GET['action'] == "delete")
    {
        foreach($_SESSION['cart'] as $keys => $values)
        {
            if($values['Item_ID'] == $_GET['ItemID'])
            {
                unset($_SESSION['cart'][$keys]);
                echo '<script>alert("Item Removed")</script>';
                echo '<script>window.location="HomePage.php"</script>';
            }
        }
    }
}

if(isset($_GET['action']))
{
    if($_GET['action'] == "clear")
    {
        session_destroy();
        header("Location: HomePage.php");
    }
}
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
            grid-template-columns:repeat(1, 1fr);
            grid-gap:1em;
            grid-row: inherit;
            grid-auto-rows: minmax(10px, auto);
            padding-left:15em;
            padding-right:15em;
            
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
            <tr>
            <tr>
                <td width = "85%" background = "Images/Banner.jpg">
                    <div class = "nested" style = "font-size: 30px; color: white; font-style: oblique;"><h1>Welcome!<br>Find the screen of your dreams!</h1></div>
                </td>
                <td class = table align = "right" width = "15%" style = "background:transparent">
                    <input type = "submit" name ="List" style = "width: 170px; height: 50px; margin-bottom: 25px;" value = "Show Items">
                    <input type = "submit" name ="Checkout" style = "width: 170px; height: 50px; margin-bottom: 25px;" value = "Checkout">
                    <input type = "submit" name ="LogOut" style = "width: 170px; height: 50px; margin-bottom: 25px;" value = "Log Out">
                </td>
            </tr>
            <tr>
            </tr>
            </tr>
        </table>
        </header>

        <div class = "home" style = "font-size: 30px; color: white; font-style: oblique" align = "center">
            <h1>Shoppig Cart: </h1> 
        </div>

        <div class = "cart">
        <table class = "table table-bordered">
            <tr>
                <th width = "10%">Product</th>
                <th width = "30%">Description</th>
                <th width = "5%">Quantity</th>
                <th width = "10%">Price Each</th>
                <th width = "10%">Total</th>
                <th width = "5%">Action</th>
            </tr>
            <?php
                if(!empty($_SESSION['cart']))
                {
                    $total = 0;
                    foreach($_SESSION['cart'] as $keys => $values)
                    {
                    ?>
                        <tr>
                            <td><?php echo $values['Item_Name']; ?></td>
                            <td><?php echo $values['Item_Description'];?></td>
                            <td><?php echo $values['Item_quantity']; ?></td>
                            <td><?php echo $values['Item_Price']; ?></td>
                            <td><?php echo number_format($values['Item_quantity'] * $values['Item_Price'], 2); ?></td>
                            <td><a href="HomePage.php?action=add&=ItemID<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Add Item</span><br></a>
                            <a href="HomePage.php?action=delete&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Remove Item</span></a></td>
                            
                        </tr>
                        <?php
                            $total = $total + ($values['Item_quantity'] * $values['Item_Price']);
                    }
                ?>
                <tr>
                    <td></td>
                    <td colspan="3" align ="right">Total</td>
                    <td align = "right">R <?php echo number_format($total, 2); ?></td>
                    <td><a href="HomePage.php?action=clear&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Empty Cart</span></a></td>
                    
                </tr>
                <?php
                }
                ?>
</table>
</form>

</div>     
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
            <form method = "post" action="HomePage.php?action=add&ItemID=<?php echo $row['ItemID'];?>">
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
            if(!empty($_SESSION['cart']))
            {
                if(isset($_POST['add_to_cart']))
                {   
                    if($_POST['quantity'] > $_POST['hidden_quantity'])
                    {
                        unset($_SESSION['cart'][$keys]);
                        echo '<script>alert("Quantity Exceeded")</script>';
                        echo '<script>window.location="HomePage.php"</script>';
                    }
                    else{
                        
                    }
                    
                }
                else
                {

                }
            }
    }
    ?>
    </div>
</body>
</html>

<?php
?>