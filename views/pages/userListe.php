<?php

// Template de type page, affiche la liste de tous les utilisateurs

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php include "views/parts/head.php" ?>
    </head>
    <body>
        <?php include "views/parts/header.php" ?>
        <main>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                </tr>
                <?php include "views/parts/ligneListeUser.php" ?>
            </table>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
