<?php
    ob_start();
    session_start();
    include('includes/functions.inc.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Mon compte</title>
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
    <div class="account-bg container-fluid">
        <div class="account">
            <a class="link col-md-2" href="account.php?action=show_orders&status=1" >Commandes complétés</a>
            <a class="link col-md-2" href="account.php?action=show_orders&status=0">Commandes attendues</a>
            <a class="link col-md-2 col-md-offset-2" href="order.php">Commander rapidement</a>
            <a class="link col-md-2" href="account.php?action=logout">Se déconnecter <i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </div>
        <?php
        if(!isset($_GET['status'])) {
            echo "";
        } else {
        $orders = showOrders($connect);
        if(!empty($orders)) {
            $client = getClient($connect);
            $name = $client['name'];
            $phone = $client['phone'];
            $address = $client['address'];

            foreach($orders as $order) {
                $id_order = $order[0];
                $date_create = $order[1];
                $date_delivery = $order[2];
                $carts = showCarts($connect);
            ?>
            <div class="row">
                <div class="order-account col-md-8">
                    <table class="order-table order-table-account table">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                            $i=0;
                            foreach($carts as $k=>$cart) {
                                $cart_id_order = $cart[0];
                                $image = $cart[1];
                                $name_wine = $cart[2];
                                $price = $cart[3];
                                $quantity = $cart[4];
                                $total_price = $price * $quantity;
                                if ($cart_id_order == $id_order) { 
                                    $i++;
                                    $total+=$total_price;
                                    echo 
                                    '<tr>
                                        <td>'.$i.'</td>
                                        <td>
                                            <img src="img/items/'.$image.'" alt=""/>
                                           <p>'.$name_wine.'</p>
                                        </td>
                                        <td>'.$price.' €</td>
                                        <td>'.$quantity.'</td>
                                        <td>'.$total_price.' €</td>
                                    </tr>';   
                                }  
                            } ?>
                        </tbody>
                    </table> 
                <div class="order-info col-md-6">
                    <h2><?php echo "Commande № ".$order[0]; ?></h2>
                    <p>Date(commande):<span><?php echo "[".$order[1]; ?></span></p>
                    <p>Date(livraison):<span><?php echo "[".$order[2]; ?></span></p>
                    <p>Nom:<span><?php echo $name; ?></span></p>
                    <p>Téléphone:<span><?php echo $phone; ?></span></p>
                    <p>Adresse:<span><?php echo $address; ?></span></p>
                    <p>Montant totale:<span><?php echo $total; ?> €</span></p> 
                </div> 
                </div>
            </div>
            <?php  } 
            } else { 
            echo "<div class='no-items'><p>Vous n'avez pas encore des commandes.</p>
            <a href='shop.php'>Visitez la boutique</a></div>"; 
            }
        }?>
    </div>
</body>
    <footer>
        <?php require('footer.html'); ?>
    </footer>
</html>