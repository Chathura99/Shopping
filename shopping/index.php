<?php
    session_start();
    $db_name = "shopping";
    $connection = mysqli_connect("localhost","root","",$db_name);

    if(isset($_POST["add"])){
        if(isset($_SESSION["shopping_cart"])){
            $item_array_id = array_column($_SESSION["shopping_cart"],"product_id");
            if(!in_array($_GET["id"],$item_array_id)){
                $count = count($_SESSION["shopping_cart"]);
                $item_array = array(
                    'product_id' => $_GET["id"],
                    'product_name' => $_POST["hidden_name"],
                    'product_price' => $_POST["hidden_price"],
                    'product_quantity' => $_POST["quantity"],
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
                header("location:index.php");
            }else{
                header("location:index.php");
            }
        }else{
            $item_array = array(
                'product_id' => $_GET["id"],
                'product_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'product_quantity' => $_POST["quantity"],
            );
            $_SESSION["shopping_cart"][0] = $item_array;
        }
    }

    if(isset($_GET["action"])){
        if($_GET["action"] == "delete"){
            foreach($_SESSION["shopping_cart"] as $keys => $value){
                if($value["product_id"] == $_GET["id"]){
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>window.location="index.php"</script>';
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Hawlok yummyMall</h2>
    <div class="container">
                <?php
                    $query = "select * from product order by id asc";
                    $result = mysqli_query($connection,$query);
                    if(mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_array($result)){
                            ?>
                            <div class="product">
                                <form method="post" action="index.php?action=add&id=<?php echo $row["id"];?>">
                                        <img src="<?php echo $row["image"];?>">
                                        <h5><?php echo $row["description"];?></h5>
                                        <h5><?php echo $row["price"];?></h5>
                                        <input type="text" name="quantity" value="1"><br>
                                        <input type="hidden" name="hidden_name" value="<?php echo $row["description"];?>">
                                        <input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>">
                                        <input type="submit" name="add" class="btn">
                                </form>
                            </div>
        <?php
                }
            }
        ?>
    </div>
    <h2>Shopping Cart Details</h2>
        <div class="detail">
            <table class="table table-bordered">
            <tr>
                <th>Product Description</th>
                <th>Quantity</th>
                <th>Price Details</th>
                <th>Total Price</th>
                <th>Remove Item</th>
            </tr>
            <?php
                if(!empty($_SESSION["shopping_cart"])){
                    $total=0;
                    foreach($_SESSION["shopping_cart"] as $key => $value){
                    ?>
                <tr>
                        <td><?php echo $value["product_name"];?></td>
                        <td><?php echo $value["product_quantity"];?></td>
                        <td><?php echo $value["product_price"];?></td>
                        <td><?php echo number_format($value["product_quantity"]*$value["product_price"],2);?></td>
                        <td><a href="index.php?action=delete&id=<?php echo $value["product_id"]; ?>">Remove</a></td>
                </tr>
                <?php
                    $total = $total + ($value["product_quantity"]*$value["product_price"]);
                    }
                ?>
                <tr>
                        <td>Total</td>
                        <td><?php echo number_format($total,2);?></td>
                        <td></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>