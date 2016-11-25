<?php
    ob_start();
    session_start();
    include('includes/functions.inc.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Boutique</title>
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
        <meta charset="utf-8">
    </head>
    <body>
       <form action="shop.php?action=show" method="post" name="main_form">
        <div class="filters"> 
            <h2>Les Filtres</h2>
            <div class="filters-info col-md-12">
               <div class="filter-price filter">
                    <h3>Prix</h3>
                    <?php  
                        $query = "SELECT MIN(price) min, MAX(price) max FROM wines";
                        $result = $connect->query($query);
                        $price = $result->fetch(PDO::FETCH_ASSOC);
                        $min = $price['min'];
                        $max = $price['max'];
                        $start_price = $_POST['start_price'];
                        $end_price = $_POST['end_price'];
                        echo "
                    <input type=\"text\" name=\"start_price\" value=\"".((!empty($start_price))?(number_format($start_price, 2, '.', ' ')):($min))."\" class=\"input\"/>
                     —  
                    <input type=\"text\" name=\"end_price\" value=\"".((!empty($end_price))?(number_format($end_price, 2, '.', ' ')):($max))."\" class=\"input\"/>";
                        ?> 
                </div>
                <div class="filter-color filter">
                    <h3>Couleur</h3>
                    <table>
                        <tbody>
                        <?php
                            $colors = getColors($connect);
                            foreach($colors as $color) {
                               $id_color = $color[0];
                               $name_color = ucfirst($color[1]);
                               echo 
                               "<tr>
                                   <td>
                                       <input name=\"color\" type=\"radio\" class=\"radio\" id=\"color".$id_color."\" value=\"".$name_color."\"".((!empty($_POST['color']) && $_POST['color']=="".$name_color."")?(" checked"):("")).">
                                       <label for=\"color".$id_color."\">".$name_color."</label>
                                   </td>
                               </tr>";
                            }?>
                        </tbody>
                    </table>
                </div>
                <div class="filter-type filter">
                    <h3>Type</h3>
                    <?php 
                        $types = getTypes($connect);
                        foreach($types as $type) {
                           $id_type = $type[0];
                           $name_type = $type[1];
                            echo
                            "<div class='col-md-6'>
                                <input name=\"type[]\" type=\"checkbox\" class=\"checkbox\" id=\"type".$id_type."\" value=\"".$name_type."\"".((!empty($_POST['type']) && in_array($name_type, $_POST['type']))?(" checked"):("")).">
                                <label for=\"type".$id_type."\">".$name_type."</label>
                            </div>";
                        }?>  
                </div>
                <div class="filter-date filter">
                    <h3>Maîtrise de soi</h3>
                    <select name="date" size="1" class="input">
                        <option  value="Toutes">Toutes</option>
                            <?php                             
                            for($i=2013; $i<2016; $i++) {
                                echo "<option value=".$i."".((!empty($_POST['date']) && $_POST['date']==$i)?(" selected"):("")).">".$i."</option>";
                            } ?>
                    </select>    
                </div>
                <div class="filter-size filter">
                    <h3>Taille</h3> 
                    <table>
                        <tr>
                           <?php 
                            $sizes = getSizes($connect);
                            foreach($sizes as $size) {
                                $id_size = $size[0];
                                $value_size = $size[1];
                                echo
                                "<td>
                                    <p><input name=\"size[]\" type=\"checkbox\" class=\"checkbox\" id=\"size".$id_size."\" value=\"".$value_size."\"".((!empty($_POST['size']) && in_array($value_size, $_POST['size']))?(" checked"):("")).">
                                    <label for=\"size".$id_size."\">".$value_size."L</label></p>
                                </td>"; 
                            }?>
                        </tr>
                    </table>      
                </div>
                <div class="filter-country filter">
                    <h3>Pays</h3>
                    <table>
                        <tbody>
                            <?php
                                $countries = getCountries($connect);
                                foreach($countries as $country) {
                                    $id_country = $country[0];
                                    $name_country = $country[1];
                                    $image_country = $country[2];
                                    echo 
                                    "<tr>
                                       <td style=\"background: url(img/flags/".$image_country.") no-repeat;  background-size: cover;\">
                                           <input name=\"country\" type=\"radio\" class=\"radio\" id=\"country".$id_country."\" value=\"".$name_country."\"".((!empty($_POST['country']) && $_POST['country']=="".$name_country."")?(" checked"):("")).">
                                           <label for=\"country".$id_country."\">".$name_country."</label>
                                       </td>
                                    </tr>";
                                }?>
                        </tbody>
                   </table> 
                </div>
                <input type="submit" value="Appliquer" class="submit" name="filters_submit" />
            </div>
        </div>   
        <header class="shop-header">
           <?php require('header.php'); ?>
        </header>
        <div class="sorting container-fluid col-md-offset-2 ">
            <div class="sort">
                <select name="sorts" size="1" class="input">
                    <?php echo "
                    <option value='name'".((!empty($_POST['sorts']) && $_POST['sorts']=='name')?(" selected"):("")).">par nom</option>
                    <option value='price'".((!empty($_POST['sorts']) && $_POST['sorts']=='price')?(" selected"):("")).">par prix</option>
                    <option value='size'".((!empty($_POST['sorts']) && $_POST['sorts']=='size')?(" selected"):("")).">par taille</option>
                    "; ?>
                </select>
                <input type="submit" value="trié" class="sort-submit" name="sort_submit" />
            </div>
            <?php countWine($connect, $where, $sorts); ?>
        </div>
          </form>
        <div class="shop container-fluid col-md-offset-2 ">
            <div class="container">
            <?php 
                $wines = getAllWine($connect, $where, $sorts);
                foreach($wines as $wine) {
                    $id = $wine[0];
                    $name = $wine[1];
                    $price = $wine[2];
                    $date = $wine[3];
                    $image = $wine[4];
                    $size = $wine[5]; 
                    $type = $wine[6];
                    $country = $wine[7];
                    $color = $wine[8]; 
                    echo '
                        <form action="cart.php?action=add&id_wine='.$id.'" method="post" name="cart_form" >
                            <div class="item col-md-3">
                                <div class="item-img">
                                    <img src="img/items/'.$image.'" alt="" />
                                    <img id="item-color" src="img/colors/'.$color.'" alt="" />
                                </div>
                                <div class="item-info">
                                    <p><span>'.$name.'</span></br>'.$type.', '.$size.'L, '.$country.'('.$date.')</p>
                                    <div class="row">
                                        <p id="item-price">'.$price.'€</p>
                                        <input type="number" name="quantity" class="dish-count" min="1" step="1" value="1" />    
                                    </div>
                                    <input type="submit" class="button-add" value="Ajouter" /> 
                                </div>
                            </div>
                        </form>';
                } 
            ?>           
            </div>
            <footer>
                <?php require('footer.html'); ?>
            </footer>
        </div>  
    </body>
</html>