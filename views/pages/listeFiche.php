<?php

// Template de type page, affiche les fiches issues d'une liste
// A besoin de : 
//              - $liste, liste contenant les différentes fiches que l'on liste

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
                    <th>Titre</th>
                    <th>Résumé</th>
                    <th>Auteur</th>
                </tr>
                <?php include "views/parts/ligneListeFiche.php" ?>
            </table>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
