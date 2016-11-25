<?php
    ob_start();
    session_start();
    include('lib/password.php');
    include('includes/functions.inc.php');
    $msg = "";
    //$account_link = "";
    //$connect = new PDO("mysql:host=$host; dbname=$dbname", $login, $password);
    if(isset($_POST['register'])){
        $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;

        $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
        $stmt = $connect->prepare($sql);

        $stmt->bindValue(':username', $username);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['num'] > 0){
            $msg = "<p class='incorrect'>Ce nom d'utilisateur existe déjà!</p>";
        } else {
            $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
            
            $connect->beginTransaction();
            try {
                $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
                $stmt = $connect->prepare($sql);

                $stmt->bindValue(':username', $username);
                $stmt->bindValue(':password', $passwordHash);
                $stmt->execute();
                
                $last_id = $connect->lastInsertId();

                $sql = "INSERT INTO clients (name, phone, email, address, id_user) VALUES(:name, :phone, :email, :address,  :id_user)";
                $stmt = $connect->prepare($sql);

                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':phone', $phone);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':address', $address);
                $stmt->bindValue(':id_user', $last_id);
                $stmt->execute();

                $connect->commit();
                
                $msg = "<p>Merci pour l'enregistrement. Cliquer sur <a href='index.php#login'>S'inscrire</a></p>";
            } 
            catch (PDOException $error) {
                $connect->rollback();
                exceptionErrorHandler($error);
                exit;
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>S'inscrire</title>
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
        <meta charset="utf-8">
    </head>
    <body>
        <header class="main-header">
            <?php require('header.php'); ?>
            <div class="background-reg">
                <div class="container-fluid">
                    <div class="col-md-5 col-md-offset-6">
                        <h3>Créez votre compte</h3>
                        <?php echo ((!empty($msg))?($msg):(null));?>   
                    </div>

                    <div class="reg col-md-5 col-md-offset-6">
                       <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                         ?>" name="reg_form">
                          <?php echo "
                            <input type=\"text\" class=\"login-input\" name=\"name\" placeholder=\"Votre nom\" value=\"".((!empty($_POST['name']))?($_POST['name']):(null))."\" required/>
                            <input type=\"tel\" class=\"login-input\" name=\"phone\" placeholder=\"Votre téléphone\" value=\"".((!empty($_POST['phone']))?($_POST['phone']):(null))."\" required/>
                            <input type=\"email\" class=\"login-input\" name=\"email\"  placeholder=\"Votre email\" value=\"".((!empty($_POST['email']))?($_POST['email']):(null))."\" />
                            <input type=\"text\" class=\"login-input\" name=\"address\" placeholder=\"Votre adresse\" value=\"".((!empty($_POST['address']))?($_POST['address']):(null))."\" />
                            <input type=\"text\" class=\"login-input\" name=\"username\" placeholder=\"Votre login\" value=\"".((!empty($_POST['username']))?($_POST['username']):(null))."\" required/>
                            <input type=\"password\" class=\"login-input\" name=\"password\" placeholder=\"Votre code d'accès\" value=\"".((!empty($_POST['password']))?($_POST['password']):(null))."\" required/>
                          ";?>
                           <input type="submit" class="button-log" name="register" value="se connecter" />     
                       </form>
                    </div>   
               </div>
                
            </div>
        </header>
        <footer>
            <?php require('footer.html'); ?>
        </footer>
    </body>
</html>