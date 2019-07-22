<?php

// Template de type page, affiche un formulaire de modification d'un utilisateur
// A besoin de :
//          - $this, l'objet courant

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
            <form class="modif" method="POST" action="index.php?module=user&action=update&id=<?= session::getUserId() ?>">
                <label for="nom">Nom : </label>
                <input type="text" id="nom" name="nom" value="<?= $this->html("nom") ?>" />
                <label for="prenom">Prénom : </label>
                <input type="text" id="prenom" name="prenom" value="<?= $this->html("prenom") ?>" />
                <label for="mdp">Mot de passe : </label>
                <input type="password" id="nom" name="mdp" />
                <label for="mail">Adresse email : </label>
                <input type="email" id="mail" name="mail" value="<?= $this->html("mail") ?>" />
                <input type="submit" value="Modifier" />
            </form>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
