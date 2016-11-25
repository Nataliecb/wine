<?php
   ob_start();
   session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Galerie</title>
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
    <div class="gallery container-fluid">
        <div class="photo col-md-4">
            <img src="img/gallery/location.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Notre enterprise</h2>
                <p>Notre entreprise a fait la boutique en ligne</p>
                <h4>22-10-2016</h4>
            </span>
        </div>
        <div class="photo col-md-4">
            <img src="img/gallery/event.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Les soirées joyeuses</h2>
                <p>Avec notre vin vos soirées vont passer plus cordialement et plus agréablement</p>
                <h4>29-10-2016</h4>
            </span>
        </div>
        <div class="photo col-md-4">
            <img src="img/gallery/grape.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Nos plantations</h2>
                <p>Nous cultivons la vigne sur les meilleures vignobles</p>
                <h4>03-11-2016</h4>
            </span>
        </div>
        <div class="photo col-md-4">
            <img src="img/gallery/vermut.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Le vermouth</h2>
                <p>Il est communément servi pour l’apéritif, agrémenté d’un zeste de citron ou d’orange, frais ou avec des glaçons</p>
                <h4>11-11-2016</h4>
            </span>
        </div>
        <div class="photo col-md-4">
            <img src="img/gallery/glasses.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Les avantages du vin</h2>
                <p>Le vin rouge est très bon pour notre santé</p>
                <h4>20-11-2016</h4>
            </span>
        </div>
        <div class="photo col-md-4">
            <img src="img/gallery/wine.jpg" class="pic-image" alt="" />
            <span class="photo-info effect">
                <h2>Maturation du vin</h2>
                <p>Le fût dote le vin de son caractère boisé et de cette odeur vanillée si typique qui signe le passage sous bois</p>
                <h4>22-11-2016</h4>
            </span>
        </div>
    </div>
</body>
    <footer>
        <?php require('footer.html'); ?>
    </footer>
</html>