<?php
    ob_start();
    session_start();
    include('includes/functions.inc.php'); 
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Panier</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
</head>
<body>
   
    <header>
        <?php require('header.php'); ?>
    </header>
    <div class="cart container-fluid">
    <form action="cart.php" method="post">
    <div class="col-md-6">
        <h3>Votre panier</h3>   
    </div>
       <?php 
        if (count($_SESSION["cart_item"]) == 0) { ?>
            <div class='no-items'><p>Il n'y a pas des produits sélectionnés.</p>
            <a href='shop.php'>Visitez la boutique</a></div>
       <?php } else { ?>
            <div class="col-md-3 col-md-offset-3">
                <a id="empty" class="submit" href="cart.php?action=empty">Panier vide</a> 
            </div>
            <table class="cart-table table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Nom</th>
                    <th>Size</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total prix</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($_SESSION["cart_item"] as $k=>$item) {
                $i++;
                if (isset($_POST['update_submit'])) {
                    $_SESSION["cart_item"][$k]["quantity"] = $_POST["count"][$k];
                    $_SESSION["cart_item"][$k]["total_price"] = $_POST["count"][$k] * $item["price"];
                }
                echo '
                <tr>
                    <td>'.$i.'</td>
                    <td>
                        <img src="img/items/'.$item["image"].'" alt=""/>
                        <p>'.$item["name"].'</p>
                    </td>
                    <td>'.$item["size"].' L</td>
                    <td>'.$item["price"].' €</td>
                    <td><input type="number" name="count['.$item["name"].']" class="dish-count" min="1" step="1" value="'.$_SESSION["cart_item"][$k]["quantity"].'" /></td>
                    <td>'.$_SESSION["cart_item"][$k]["total_price"].' €</td>
                    <td><a href="cart.php?action=remove&id='.$item["id_wine"].'" class="delete"><img src="img/delete-hover.png" alt="" /></a></td>
                </tr>';
                $item_total += $_SESSION["cart_item"][$k]["total_price"];
               } ?>
            </tbody>
        </table>
        <div class="update col-md-8">
            <h4>Montant totale à payer: <span><?php echo $item_total; ?> €</span></h4>
            <input type="submit" value="Mettre à jour" class="submit" name="update_submit" />   
        </div>
        <div class="continue col-md-4">
           <a href="order.php" class="submit">Continuer</a>
        </div>
       <?php } ?>
        </form>
    </div>
    <footer>
        <?php require('footer.html'); ?>
    </footer> 
</body>
</html>