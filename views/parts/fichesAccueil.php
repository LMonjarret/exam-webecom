<?php

// Template de type parts, affiche cinq fiches résumées sur la page d'accueil
// A besoin de :
//          - $liste, tableau contenant les cinq objets listes

includeClass("model_fiche");

$liste = new model_fiche();
$liste = $liste->dernieresFiches();

foreach ($liste as $fiche) {
    $resume = str_split($fiche->html("texte"), "15");
    
    ?>

<div class="fiche-accueil">
    <p><?= $fiche->html("titre") ?></p>
    <p><?= $resume[0] ?>...</p>
    <a href="index.php?module=fiche&action=detail&id=<?= $fiche->getId() ?>">Lire la suite</a>
</div>

<?php
}