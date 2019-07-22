<?php

// Template de type page, affiche le formulaire de connexion à l'application

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
            <h1>Connexion à votre espacement membre</h1>
            <?php include "views/parts/formConnexionUser.php" ?>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
