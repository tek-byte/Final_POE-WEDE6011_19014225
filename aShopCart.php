<?php

function addToCart()
    {
        if(isset($_SESSION['cart']))
        {
            $item_array_id = array_column($_SESSION['cart'],"ItemID");
            $itemid = $_GET['ItemID'];
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
                echo '<script>window.location="myShop.php"</script>';
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

    
    function removeItem()
    {
        if($_GET['action'] == "delete")
        {
            foreach($_SESSION['cart'] as $keys => $values)
            {
                if($values['Item_ID'] == $_GET['ItemID'])
                {
                    unset($_SESSION['cart'][$keys]);
                    echo '<script>alert("Item Removed")</script>';
                    //echo '<script>window.location="myShop.php"</script>';
                    shopCart();
                }
            }
        }
    }

    
    function emptyShoppingCart()
    {
        if($_GET['action'] == "clear")
        {
            unset($_SESSION['cart']);
            //session_destroy();
            shopCart();
            
        }
    }


    function shopCart()
    {   
        ?>  
        <div class = "cart" style = "font-size: 30px; color: white; font-style: oblique" align = "center">
            
            <table class = "table table-bordered" style="border:3px solid black">
            <th colspan = 6 align = center  background = "Images/cartShop.jpg" width="350" height="250" style = "padding:25px;"><h1><br>Shoppig Cart: </h1> 
                <tr style="border:3px solid black">
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
                            <tr style="border:3px solid black">
                                <td class = "cartshop"><?php echo $values['Item_Name']; ?></td>
                                <td class = "cartshop"><?php echo $values['Item_Description'];?></td>
                                <td class = "cartshop"><?php echo $values['Item_quantity']; ?></td>
                                <td class = "cartshop"><?php echo $values['Item_Price']; ?></td>
                                <td ><?php echo number_format($values['Item_quantity'] * $values['Item_Price'], 2); ?></td>
                                <td><a href="myShop.php?action=delete&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Remove Item</span></a></td>
                                
                            </tr>
                            <?php
                            $total = $total + ($values['Item_quantity'] * $values['Item_Price']);
                        }
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="3" align ="right">Total</td>
                        <td align = "right">R <?php echo number_format($total, 2); ?></td>
                        <td><a href="myShop.php?action=clear&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Empty Cart</span></a></td>
                        
                    </tr>
                    
                    <?php
                    }
                    ?>
                    </th>
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

    function checkout()
    {   
        if(!empty($_SESSION['cart']))
        {
            $total = 0;
            foreach($_SESSION['cart'] as $keys => $values)
            {
                $ord = "ord";
                $num = 0;

                $count = count($_SESSION['cart']);

                $checkId = $values['Item_ID'];
                $checkName = $values['Item_Name'];
                $checkDescription = $values['Item_Description'];
                $checkPrice = $values['Item_Price'];
                $checkQuan = $values['Item_quantity'];
                $total = $total + ($values['Item_quantity'] * $values['Item_Price']);

                $ref_number = $ord.$num.$checkQuan.$checkId.$count;
                
                //$ref_number = md5($total);
                
                //echo $checkId;
                //echo $checkName;
                //echo $checkDescription;
                //echo $checkPrice;
                //echo $checkQuan;
                //"INSERT INTO tbl_orderdetail (detID, ItemID, OrderID, Quantity, Discount, Amount) VALUES ('','','','','','')"
                
            }
            ?>  
            <div class = "cart" style = "font-size: 30px; color: white; font-style: oblique" align = "center">
                
                <table class = "table table-bordered" style="border:3px solid black">
                <th colspan = 6 align = center  background = "Images/cartShop.jpg" width="350" height="250" style = "padding:25px;"><h1><br>Check out:<br><br>Reference Number:<br><?php echo $ref_number;?></h1> 
                    <tr style="border:3px solid black">
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
                                <tr style="border:3px solid black">
                                    <td class = "cartshop"><?php echo $values['Item_Name']; ?></td>
                                    <td class = "cartshop"><?php echo $values['Item_Description'];?></td>
                                    <td class = "cartshop"><?php echo $values['Item_quantity']; ?></td>
                                    <td class = "cartshop"><?php echo $values['Item_Price']; ?></td>
                                    <td ><?php echo number_format($values['Item_quantity'] * $values['Item_Price'], 2); ?></td>
                                    <td><a href="myShop.php?action=delete&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Remove Item</span></a></td>
                                    
                                </tr>
                                <?php
                                    $total = $total + ($values['Item_quantity'] * $values['Item_Price']);
                            }
                        ?>
                        <tr>
                            <td></td>
                            <td colspan="3" align ="right">Total</td>
                            <td align = "right">R <?php echo number_format($total, 2); ?></td>
                            <td><a href="myShop.php?action=clear&ItemID=<?php echo $values['Item_ID']; ?>"><span class = "text-danger">Empty Cart</span></a></td>
                            
                        </tr>
                        
                        <?php
                        }
                        ?>
                        </th>
                </table>
                
                <div class = "nested">
                    <form class = "nested" method = "post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type = "submit" name = "payNow"  value="Pay Now!" style = "width: 400px; height: 75px; margin-bottom: 25px; color :white;"/>
                        <input type  = "submit" name = "cancel"  value="Cancel" style = "width: 400px; height: 75px; margin-bottom: 25px; color :white;"/>  
                    </form>
                
                </div>
            </div>

        <?php
            
        }
        else
        {
            echo '<script>alert("Shopping Cart is Empty!")</script>';
            shopCart();

        }
    }

    
    /////////Needs Research & Completion 
    function incrementItem()
    {
    
    }
    
    function decrementItem()
    {
    
    }

?>

