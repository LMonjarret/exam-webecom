<?php

// Template de type page, contient la page d'accueil de l'application "Mes fiches jardinages"

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php include "views/parts/head.php" ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <?php include "views/parts/header.php" ?>
        <main>
            <?php 
            
            if (session::getConnected()) {
                include "views/parts/_accueilConnected.php";
            } else {
                include "views/parts/_accueilDisconnected.php";
            }
            
            ?>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
