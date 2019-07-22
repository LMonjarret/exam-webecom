<?php

// Template de type part, affiche tous les commentaires d'une fiche du plus récent au plus ancien
// A besoin de : 
//          - $liste, tableau contenant tous les commentaires d'une fiche

includeClass("model_commentaire");
$liste = new model_commentaire();
$liste = $liste->listeCommentaires($this->getId());

foreach ($liste as $commentaire) {
    ?>

<div class="commentaire">
    <p class="auteur">Par <a href="index.php?module=user&action=profil&id=<?= $commentaire->getValue("auteur") ?>"><?= ucfirst($commentaire->get("auteur")->html("nom"))." ".ucfirst($commentaire->get("auteur")->html("prenom")) ?></a>, <?= $commentaire->html("dt_creation") ?></p>
    <p><?= $commentaire->html("texte") ?></p>
</div>

<?php
}