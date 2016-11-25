<?php
    ob_start();
    session_start();
    include('includes/functions.inc.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Commande</title>
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
   <?php 
    if(isset($_POST['order_submit']) && !empty($_SESSION["cart_item"])) {
        $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
        $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $suggestions = !empty($_POST['suggestions']) ? trim($_POST['suggestions']) : null;
        $date_create = date("Y-m-d");
        $time_create = date("H:i:s");
        if (isset($_POST['date'])) {
            $date = DateTime::createFromFormat('d-m-y', $_POST['date']);
            $date_delivery = $date->format('Y-m-d');
        }
        if (!empty($_POST['time'])) {
            $time = DateTime::createFromFormat('H:i', $_POST['time']);
            $time_delivery = $time->format('H:i:s');
        } else {
            $time_delivery = "";
        }
        $msg = "";
        if(isset($_SESSION['username'])) {
            $connect->beginTransaction();
            try {
                $sql = "UPDATE clients SET name = :name, phone = :phone, email = :email, address = :address WHERE id_user = :id_user";
                $stmt = $connect->prepare($sql);

                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':phone', $phone);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':address', $address);
                $stmt->bindValue(':id_user', $_SESSION['id_user']);
                $stmt->execute();

                $sql = "SELECT id_client FROM clients WHERE id_user = :id_user";
                $stmt = $connect->prepare($sql);

                $stmt->bindValue(':id_user', $_SESSION['id_user']);
                $stmt->execute();
                if ($stmt->rowCount() > 0){
                    $client = $stmt->fetch(PDO::FETCH_ASSOC);
                    $client_id = $client['id_client'];
                }
                $connect->commit();
            } 
            catch (PDOException $error) {
                $connect->rollback();
                exceptionErrorHandler($error);
                exit;
            }
        } else {
            $connect->beginTransaction();
            try {
                $sql = "INSERT INTO clients (name, phone, email, address, id_user) VALUES (:name, :phone, :email, :address, :id_user)";
                $stmt = $connect->prepare($sql);

                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':phone', $phone);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':address', $address);
                $stmt->bindValue(':id_user', NULL);
                $stmt->execute();

                $client_id = $connect->lastInsertId();

                $connect->commit();
            } 
            catch (PDOException $error) {
                $connect->rollback();
                exceptionErrorHandler($error);
                exit;
            }
        }
        $connect->beginTransaction();
        try {
            $sql = "INSERT INTO orders (id_client, date_create, time_create, date_delivery, time_delivery, suggestions) VALUES (:id_client, :date_create, :time_create, :date_delivery, :time_delivery, :suggestions)";
            $stmt = $connect->prepare($sql); 
            
            $stmt->bindValue(':id_client', $client_id);
            $stmt->bindValue(':date_create', $date_create);
            $stmt->bindValue(':time_create', $time_create);
            $stmt->bindValue(':date_delivery', $date_delivery);
            $stmt->bindValue(':time_delivery', $time_delivery);
            $stmt->bindValue(':suggestions', $suggestions);
            $stmt->execute();

            $order_id = $connect->lastInsertId();

            $sql = "INSERT INTO cart_items (id_order, id_wine, quantity) VALUES (:id_order, :id_wine, :quantity)";
            $stmt = $connect->prepare($sql);
            
            $stmt->bindValue(':id_order', $order_id);
            foreach ($_SESSION["cart_item"] as $k=>$item) {
                $stmt->bindValue(':id_wine', $item['id_wine']);
                $stmt->bindValue(':quantity', $item['quantity']);
                $stmt->execute();
            }
            $connect->commit();
            $msg = "<h5>Merci pour votre commande!</h5>";
            unset($_SESSION["cart_item"]);
        } 
        catch (PDOException $error) {
            $connect->rollback();
            exceptionErrorHandler($error);
            exit;
        }
    }
    ?>
    <div class="order container-fluid">
        <h3>Votre commande</h3>
        <?php if(!empty($_SESSION["cart_item"])) { ?>
        <div class="cart-part col-md-6">
            <table class="order-table table">
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
                    $i = 0;
                    foreach ($_SESSION["cart_item"] as $k=>$item) {
                        $i++;
                        echo '
                        <tr>
                            <td>'.$i.'</td>
                            <td>
                                <img src="img/items/'.$item['image'].'" alt=""/>
                               <p>'.$item['name'].'</p>
                            </td>
                            <td>'.$item['price'].' €</td>
                            <td>'.$item['quantity'].'</td>
                            <td>'.$item['total_price'].' €</td>
                        </tr>';
                        $item_total += $item['total_price'];
                    }?>

                </tbody>
            </table> 
            <p>Montant totale à payer: <span><?php echo $item_total; ?> €</span></p>   
        </div>
        <?php } else { ?>
            <div class="cart-part-empty col-md-6">
                <p>Il n'y a pas des produits sélectionnés.</p><a href='shop.php'>Visitez la boutique</a>
            </div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                         ?>" name="order_form">
            <div class="order-part col-md-5">
                <?php 
                if(!isset($_SESSION['id_user']) && !isset($_POST['order_submit'])) {
                 echo "<h5>Si vous avez un compte sur notre site - <a href='index.php#login'>SE CONNETER</a></h5>";
                }
                if(!empty($msg)) {
                    echo $msg;
                }
                if(!empty($_POST['name'])) {
                    $name = $_POST['name'];
                } elseif(isset($_SESSION['name'])) {
                    $name = $_SESSION['name'];
                }
                if(!empty($_POST['phone'])) {
                    $phone = $_POST['phone'];
                } elseif(isset($_SESSION['phone'])) {
                    $phone = $_SESSION['phone'];
                }
                if(!empty($_POST['address'])) {
                    $address = $_POST['address'];
                } elseif(isset($_SESSION['address'])) {
                    $address = $_SESSION['address'];
                }
                if(isset($_POST['email'])) {
                    $email = $_POST['email'];
                } elseif(isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                }
               echo "
                <input type=\"text\" class=\"order-input\" name=\"name\" placeholder=\"Votre nom\" value=\"".((!empty($name))?($name):(null))."\" required/>
                <input type=\"tel\" class=\"order-input\" name=\"phone\" placeholder=\"Votre téléphone\" value=\"".((!empty($phone))?($phone):(null))."\" required/>
                <input type=\"text\" class=\"order-input\" name=\"address\" placeholder=\"Votre adresse\" value=\"".((!empty($address))?($address):(null))."\" required/>
                <input type=\"email\" class=\"order-input\" name=\"email\" placeholder=\"Votre email\" value=\"".((!empty($email))?($email):(null))."\" />
                <textarea rows=\"3\" cols=\"45\" class=\"order-input\" name=\"suggestions\" placeholder=\"Suggestions de votre commande\">".((!empty($_POST['suggestions']))?($_POST['suggestions']):(""))."</textarea>";
                ?>
                <span>
                    <select name="date" size="1" id="date" class="order-input">';
                        <?php 
                        for($i=0;$i<6;$i++){
                            $cdate = date('d-m-y', time()+$i*24*60*60);
                            echo "<option value=".$cdate."".((!empty($_POST['date'])&& $_POST['date']==$cdate)?(" selected"):("")).">".$cdate."</option>";
                        }?>
                    </select>
                    <?php
                    echo "
                    <input type=\"time\" id=\"time\" class=\"order-input\" name=\"time\" value=\"".((!empty($_POST['time']))?($_POST['time']):(null))."\" />";
                    ?> 
                    <h6>* Précisez la date et l'heure de la livraison</h6>
                </span>                 
            </div>
            <div class="return col-md-8">
                <h4>Voulez-vous continuer faire les courses?</h4> 
                <a href="shop.php" class="submit">Boutique</a>
            </div>
            <div class="continue col-md-4">
              <input type="submit" value="Commander" class="submit" name="order_submit" />     
            </div>
        </form>
    </div>
    <footer>
        <?php require('footer.html'); ?>
    </footer> 
</body>
</html>