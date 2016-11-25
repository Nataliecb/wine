<?php 
session_start();
include('includes/connect.inc.php'); 
$connect = new PDO("mysql:host=$host; dbname=$dbname", $login, $password);

/************---FILTERS---************/

if (isset($_POST['sort_submit']) || isset($_POST['filters_submit'])) {
       if (isset($_POST['sorts'])) {
           $sorts = " ORDER BY ".$_POST['sorts']."";
        } else {
            $sorts = "";
        }
    }
if (isset($_POST['filters_submit']) || isset($_POST['sort_submit'])) {
    if (isset($_POST['start_price']) || isset($_POST['end_price']) || isset($_POST['color']) || isset($_POST['size']) || isset($_POST['date']) || isset($_POST['type']) || isset($_POST['country'])) {
       $where = " WHERE";
        if (isset($_POST['start_price']) || isset($_POST['end_price']) ) {
            $start_price = $_POST['start_price'];
            $end_price = $_POST['end_price'];
            if(isset($_POST['color']) || isset($_POST['type']) || isset($_POST['date']) || isset($_POST['size']) || isset($_POST['country'])) {
                $where .= " AND w.price >= ".$start_price." && w.price <= ".$end_price."";
                if(substr($where, 7, 3) == 'AND') {
                    $where = str_replace('AND', '', $where);
                }
            } else { 
                $where .= " w.price >= ".$start_price." && w.price <= ".$end_price."";
            }
        }
        if (isset($_POST['color'])) {
            $query = "SELECT id_color FROM colors WHERE name = '".$_POST['color']."'";
            $result = $connect->query($query);
            $color = $result->fetch(PDO::FETCH_COLUMN);
            if(isset($_POST['start_price']) || isset($_POST['end_price']) || isset($_POST['type']) || isset($_POST['date']) || isset($_POST['size']) || isset($_POST['country'])) {
                $where .= " AND col.id_color = ".$color."";
                if(substr($where, 7, 3) == 'AND') {
                    $where = str_replace('AND', '', $where);
                }
            } else {
                $where .= " col.id_color = ".$color."";
            }
        }
        if (isset($_POST['type'])) {
            if(isset($_POST['start_price']) || isset($_POST['end_price']) || isset($_POST['color']) || isset($_POST['date']) || isset($_POST['size']) || isset($_POST['country'])) {
                $where .= " AND t.name IN (". "'".implode("','", $_POST['type']) ."'".")";
                if(substr($where, 7, 3) == 'AND') {
                    $where = str_replace('AND', '', $where);
                }
            } else {
                $where .= " t.name IN (". "'".implode("','", $_POST['type']) ."'".")"; 
            }
        }
        if (isset($_POST['size'])) {
            if(isset($_POST['start_price']) || isset($_POST['end_price']) || isset($_POST['color']) || isset($_POST['type']) || isset($_POST['date']) || isset($_POST['country'])) {
                $where .= " AND s.value IN (". implode(",", $_POST['size']) .")";
                if(substr($where, 7, 3) == 'AND') {
                    $where = str_replace('AND', '', $where);
                }
            } else {
                $where .= " s.value IN (". implode(",", $_POST['size']) .")";
            }
        } 
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
            if($date == 'Toutes') {
                $where .= "";
            } else {
                if(isset($_POST['start_price']) || isset($_POST['end_price']) || isset($_POST['color']) || isset($_POST['type']) || isset($_POST['size']) || isset($_POST['country'])) {
                    $where .= " AND w.date = '".$date."'";
                    if(substr($where, 7, 3) == 'AND') {
                        $where = str_replace('AND', '', $where);
                    }
                } else {
                    $where .= " w.date = '".$date."'"; 
                }
            }
        }
        if (isset($_POST['country'])) {
            $country = ucwords($_POST['country']);
            if(isset($_POST['color']) || isset($_POST['type']) || isset($_POST['date']) || isset($_POST['size'])) {
                $where .= " AND c.name = '".$country."'";
                if(substr($where, 7, 3) == 'AND') {
                    $where = str_replace('AND', '', $where);
                }
            } else {
                $where .= " c.name = '".$country."'";
            }
        } 
    } else {
        $where = "";
    } 
   }

/************---END FILTERS---************/


if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "show":
            return getAllWine($connect, $where, $sorts);
        break;
        case "add":
           return addWine($connect);
        break;
        case "remove":
            return removeItem();
        break;
        case "empty":
            unset($_SESSION["cart_item"]);
        break;
        case "show_orders":
            return showOrders($connect);
        break;
        case "logout":
            return logOut();
        break;
        
    }
}

function logOut() {
    unset($_SESSION["id_user"]);
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    unset($_SESSION["name"]);
    unset($_SESSION["phone"]);
    unset($_SESSION["email"]);
    unset($_SESSION["address"]);
    header('Refresh: 0; URL = index.php');
}
function resultArray($result) {
    $res_array = array();
    $count = 0; 
    while($row = $result->fetch(PDO::FETCH_NUM)) {
        $res_array[$count] = $row;
        $count++;
    }
    return $res_array;
}  

function getColors($connect) {
    $query = 'SELECT id_color, name FROM colors';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;           
}

function getTypes($connect) {
    $query = 'SELECT id_type, name FROM types';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;  
}

function getSizes($connect) {
    $query = 'SELECT id_size, value FROM sizes';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;  
}

function getCountries($connect) {
    $query = 'SELECT id_country, name, image FROM countries';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;           
}

function getAllWine($connect, $where, $sorts) {
    $query = 'SELECT w.id_wine id_wine, w.name name, w.price price, w.date date, w.image image, '
        .'s.value size, t.name type, c.name country, col.image color, col.id_color FROM wines w '
        .'LEFT JOIN types t ON w.id_type=t.id_type '
        .'LEFT JOIN countries c ON w.id_country=c.id_country '
        .'LEFT JOIN colors col ON w.id_color=col.id_color '
        .'LEFT JOIN sizes s ON w.id_size=s.id_size '.$where.''.$sorts.'';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;
}

function addWine($connect) {
    if(!empty($_POST['quantity'])) {
        $query = 'SELECT w.id_wine id_wine, w.image image, w.name name, s.value size, w.price price FROM wines w '
            .'LEFT JOIN sizes s ON w.id_size=s.id_size  WHERE id_wine = "'.$_GET['id_wine'].'"';
        $result = $connect->query($query);
        while($wineById = $result->fetch(PDO::FETCH_ASSOC)) {
            $wineArray = array($wineById['name']=>array('id_wine'=>$wineById['id_wine'], 'image'=>$wineById['image'], 'name'=>$wineById['name'], 'size'=>$wineById['size'], 'price'=>$wineById['price'], 'quantity'=>$_POST['quantity'], 'total_price'=>($wineById['price'])*($_POST['quantity']) ));
            if(!empty($_SESSION["cart_item"])) {
                if(in_array($wineById['name'], $_SESSION["cart_item"])) {
                    foreach($_SESSION["cart_item"] as $key => $value) {
                        if($wineById['id_wine'] == $key) 
                            $_SESSION["cart_item"][$key]["quantity"] = $_POST["quantity"];
                    }
                } else {
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$wineArray);
                }  
            } else {
               $_SESSION["cart_item"] = $wineArray;
            }
        }
    }
}

function removeItem() {
    if(!empty($_SESSION["cart_item"])) {
        foreach($_SESSION["cart_item"] as $k=>$item) {
            if($_GET["id"] == $item['id_wine']) {
                unset($_SESSION["cart_item"][$k]);	
            }			
            if(empty($_SESSION["cart_item"])) {
                unset($_SESSION["cart_item"]);
            }	
        }
    }
}

function countWine($connect, $where, $sorts) {
    $query = 'SELECT w.name name, w.price price, w.date date, w.image image, s.value size, t.name type, '
        .'c.name country, col.image color, col.id_color, w.date FROM wines w '
        .'LEFT JOIN types t ON w.id_type=t.id_type '
        .'LEFT JOIN countries c ON w.id_country=c.id_country '
        .'LEFT JOIN colors col ON w.id_color=col.id_color '
        .'LEFT JOIN sizes s ON w.id_size=s.id_size '.$where.''.$sorts.'';
    $result = $connect->query($query);
    $count = $result->rowCount();
    echo '<p>'.$count.' produits trouvés</p>';

    if ($count == 0) {
        echo "<div class='no-items'><p>Nous sommes désolés Il n'y a pas de produits sélectionnés</p></div>";
    } /*else {
        echo '<div class="pagenator">
        <a href="" class="left"></a>
        <a href="" class="right"></a>
    </div>';
    }*/
}

function getOffers($connect) {
    $query = 'SELECT w.id_wine id_wine, w.name name, w.price price, w.date date, w.image image, '
        .'s.value size, t.name type, c.name country, col.name color FROM wines w '
        .'LEFT JOIN types t ON w.id_type=t.id_type '
        .'LEFT JOIN countries c ON w.id_country=c.id_country '
        .'LEFT JOIN colors col ON w.id_color=col.id_color '
        .'LEFT JOIN sizes s ON w.id_size=s.id_size LIMIT 1,4';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;
}

function getClient($connect) {
    $query = 'SELECT c.id_client id_client, c.name name, c.phone phone, c.address address FROM clients c '
        .'LEFT JOIN users u ON c.id_user = u.id_user WHERE u.username = "'.$_SESSION["username"].'"';
    $result = $connect->query($query);
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $client_info = $row;
    }
    return $client_info;
}

function showOrders($connect) {
    $client = getClient($connect);
    $id_client = $client['id_client'];
    $query = 'SELECT o.id_order id_order, CONCAT(DATE_FORMAT(o.date_create,"%d-%m-%y"), "] ",'
        .'DATE_FORMAT(o.time_create,"%H:%i")) AS date_create, CONCAT(DATE_FORMAT(o.date_delivery,"%d-%m-%y"), "] ",'
        .'DATE_FORMAT(o.time_delivery,"%H:%i")) AS date_delivery FROM orders o '
        .'LEFT JOIN clients c ON o.id_client = c.id_client WHERE c.id_client = '.$id_client.' AND o.status = '.$_GET['status'].'';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result;  
    
}

function showCarts($connect) {
    $query = 'SELECT c.id_order id_order, w.image image, w.name name, w.price, c.quantity quantity FROM cart_items c '
        .'LEFT JOIN orders o ON c.id_order = o.id_order '
        .'LEFT JOIN wines w ON c.id_wine = w.id_wine';
    $result = $connect->query($query);
    $result = resultArray($result);
    return $result; 
}

function exceptionErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>Mon ERREUR</b> [$errno] $errstr<br />\n";
            echo "  Erreur fatale dans la ligne $errline du fichier $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Fermeture...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>Mon AVERTISSEMENT</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            echo "<b>Mon AVIS</b> [$errno] $errstr<br />\n";
            break;

        default:
            echo "Erreur inconnue: [$errno] $errstr<br />\n";
            break;
    }
    return true;
}
?>     