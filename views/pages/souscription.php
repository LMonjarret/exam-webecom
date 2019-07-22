<?php

// Template de type page, affiche le formulaire de souscription à l'application

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php include "views/parts/head.php" ?>
        <script src="https://www.google.com/recaptcha/api.js"></script>
    </head>
    <body>
        <?php include "views/parts/header.php" ?>
        <main>
            <h1>Souscrire à un abonnement</h1>
            <?php include "views/parts/formSouscriptionUser.php" ?>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
