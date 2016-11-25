<?php
    ob_start();
    session_start();
    $account_link = "<a class='account-link' href='account.php?action=show_orders&status=1'>Compte de ".$_SESSION['username']."</a>"; 
?>
<div class="container header">
    <div class="row">
        <div class="col-md-11 col-md-offset-1">
            <nav>
                <div class="logo-holder"></div>
                <ul class="clearfix">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="gallery.php">Galerie</a></li>
                    <li><a href="shop.php?action=show" class="spacer">Boutique</a></li>
                    <li><a href="index.php#about">Association</a></li>
                    <li><a href="index.php#footer">Contacts</a></li>
                    <li><h5><?php echo count($_SESSION["cart_item"]);?></h5><a id="icon-cart" href="cart.php"></a></li>
                    <li><a id="icon-login" href="index.php#login"></a></li>
                </ul>
                <?php echo ((isset($_SESSION['username']))?($account_link):(null));?>
            </nav>
        </div>
    </div>
</div>