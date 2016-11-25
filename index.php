<?php
    ob_start();
    session_start();
    include('lib/password.php');
    include('includes/functions.inc.php');
    $msg = "";
    if(isset($_POST['login'])) {
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

        $sql = 'SELECT u.id_user id_user, u.username username, u.password password, c.name name, c.phone phone, '
            .'c.email email, c.address address FROM users u '
            .'LEFT JOIN clients c ON u.id_user = c.id_user WHERE username = :username';
        $stmt = $connect->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user === false){
            $msg = "<p class='incorrect'>Incorrect login / code d'accès!</p>";
        } else {
            $validPassword = password_verify($passwordAttempt, $user['password']);
            if($validPassword) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['password'] = $user['password'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['logged_in'] = time();
                $msg = "<p>Bonjour, ".$_SESSION['username']."! C'est<a href='account.php?action=show_orders&status=1' > votre compte</a></p>";
            } else {
                $msg = "<p class='incorrect'>Incorrect login / code d'accès!</p>";
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>WINE</title>
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
        <meta charset="utf-8">
    </head>
    <body>
        <header class="main-header">
            <?php require('header.php'); ?>
            <div class="background">
                <div class="title col-md-3">
                    <h2>Capiteux vin </h2>
                    <p>Faites d'épreuve des flaveurs nouvelles de vin avec grande qualité et petit prix</p>
                    <img src="img/grape.png" alt="" />
                    <a href="shop.php"><button class="button-start">Commencer</button></a>
                </div>
            </div>
        </header>
        <section id="about">
         <div class="about container-fluid">
			<div class="row">
			    <div class="about-img col-md-6">
					<img src="img/grape-plantation.jpg" alt="" />
				</div>
				<div class="about-text col-md-6">
                    <h2>Qui sommes-nous?</h2>
                    <h3>Meilleur magasin de vin</h3>
                    <img src="img/leaves.png" alt="" />
					<p>Pour produire de grands vins vous avez besoin d'un peu de ingredients. En effet,
tous les tels choses que le bon sol, les verges verts, la lumiere du soleil, la vigne fructifere
et les bons fûts sont nécessaires. Les vrais grands vins requérant 
quelque chose de plus - le travail pénible et la passion.</p>
                    <p>Notre raison pour l'existence consiste en augmentation de la qualité de la vie par offrir
                des vins qui vont ajouter la joie et créer les souvenirs qui durent.</p>
				</div>
			</div>
			<div class="row">
				<div class="about-text col-md-6">
					<h2>Pourquoi nous?</h2>
					<h3>Le vin le plus savoureux</h3>
					<img src="img/leaves.png" alt="" />
					<p>C'est agréable à vous apporter les vins, qui sont produits par beaucoup de vingobles remarquables.
Nous collaborons avec notre viticulteurs fantastiques en France, Italie, Espagne et autres.
Chaque bouteille du vin que nous offrons a notre relation de produire les flavuers qui sont réalisées du sol unique du chaque vingoble.</p>
					<p>Chaque vin est une spécifique, individuelle expression de tous les deux des fruits 
et le metier du viticulteur.</p>
				</div>
				<div class="about-img col-md-6">
					<img src="img/wineshop.jpg" alt="" />
				</div>
			</div>
		</div>   
        </section>
        <section id="login">
            <div class="login container-fluid">
			    <div class="row">
                   <div class="login-text col-md-3">
                        <h3>Identifiez-vous pour avoir la capabilité de voir vos commandes</h3>    
                    </div>
                    <div class="login-form col-md-5">
                       <?php echo ((!empty($msg))?($msg):(null));?>
                        <form method="post" action="index.php#login" name="log_form">
                           <?php echo "
                            <input type=\"text\" class=\"login-input\" name=\"username\" placeholder=\"Votre login\" value=\"".((!empty($_POST['username']))?($_POST['username']):(null))."\" required/>
                            <input type=\"password\" class=\"login-input\" name=\"password\" placeholder=\"Votre code d'accès\" value=\"".((!empty($_POST['password']))?($_POST['password']):(null))."\" required/>
                            ";?>
                            <input type="submit" class="button-log" name="login" value="se connecter" />     
                        </form>
                    </div>
                    <div class="registration col-md-4">
                        <h2>Nouveau client ?</h2>
                        <a class="button-log" href="register.php" />s'inscrire</a>   
                    </div>
                </div>
            </div>
        </section>
        <section id="offers">
            <div class="container-fluid">
                <div class="offers row">
                   <h3>Vous avez encore rien choisi?</h3>
                   <?php 
                    $offers = getOffers($connect);
                    foreach($offers as $offer) {
                        $id = $offer[0];
                        $name = $offer[1];
                        $price = $offer[2];
                        $date = $offer[3];
                        $image = $offer[4];
                        $size = $offer[5]; 
                        $type = $offer[6];
                        $country = $offer[7];
                        $color = $offer[8]; 
                    echo '
                    <div class="offer col-md-3">
                        <img src="img/items/'.$image.'" alt="">
                        <h4>'.$name.'</h4>
                        <p>'.$color.', '.$type.', '.$size.'L, '.$country.'('.$date.')</p>
                        <p><span>Prix: '.$price.' €</span></p>
                    </div>';
                    } ?>
                    <a href="shop.php"><button class="button-start">Commencer</button></a>
                </div>
            </div>
            <div class="icons-bg container-fluid">
                <div class="icons row">
                    <div class="icon col-md-3">
                        <img src="img/add-icon.png" alt="" />
                        <p>Ajouter facilement des produits</p>    
                    </div>
                    <div class="icon col-md-3">
                        <img src="img/hours-icon.png" alt="" />
                        <p>Boutique est ouverte 24h/24</p>    
                    </div>
                    <div class="icon col-md-3">
                        <img src="img/delivery-icon.png" alt="" />
                        <p>Livraison gratuite sur toutes sélections</p>    
                    </div>
                    <div class="icon col-md-3">
                        <img src="img/euro-icon.png" alt="" />
                        <p>Paiement par virement banque</p>    
                    </div>    
                </div>
            </div>
        </section>
        <section id="footer">
            <h3>Contactez-nous</h3>
               <img src="img/leaves.png" alt="" />
                <?php require('footer.html'); ?>
        </section>        
    </body>
</html>