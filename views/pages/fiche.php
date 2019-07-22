<?php

// Template de type page, contient le détail d'une fiche de conseil

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
            <article class="fiche">
                <?php include "views/parts/imgFiche.php" ?>
                <div class="contenu">
                    <div class="entete">
                        <div class="favori">
                            <?php include "views/parts/favori.php" ?> 
                        </div>
                        <h1><?= $this->html("titre") ?></h1>
                    </div>
                    <p class="detail">Par <a href="index.php?module=user&action=profil&id=<?= $this->getValue("auteur") ?>"><?= ucfirst($this->get("auteur")->html("nom")) ?> <?= ucfirst($this->get("auteur")->html("prenom")) ?></a>, créée <?= $this->html("dt_creation") ?>, dernière modification <?= $this->html("dt_lastModif") ?></p>
                    <p><?= $this->html("texte") ?></p>
                </div>
            </article>
            <?php include "views/parts/notes.php" ?>
            <div class="boutons">
                <?php include "views/parts/btnFiche.php" ?>
            </div>
            <div class="commentaires">
                <?php include "views/parts/formCommentaire.php" ?>
                <?php include "views/parts/commentairesFiche.php" ?>
            </div>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
