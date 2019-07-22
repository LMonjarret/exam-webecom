<?php

// Template de type page, affiche le formulaire d'une fiche

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
            <h1>Créer une fiche de conseil</h1>
            <form enctype="multipart/form-data" action="index.php?module=fiche&action=insert" method="POST" class="form-fiche">
                <label for="titre">Titre de votre fiche :</label>
                <input type="text" id="titre" name="titre" value="<?= $this->html("titre") ?>" />
                <label for="texte">Contenu de votre fiche :</label>
                <textarea id="texte" name="texte"><?= $this->html("texte") ?></textarea>
                <label for="img">Image (optionnelle)</label>
                <input type="file" name="img" />
                <input type="submit" value="Valider" />
            </form>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
