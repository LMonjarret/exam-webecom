<?php

// Template de type part, ajoute un bouton "ajouter aux favoris" ou "enlever aux favoris"
// A besoin de : 
//          - $objet model_favori de la fiche que l'on consule

includeClass("model_favori");

$favori = new model_favori();

$favori->load($this->getId());

if (empty($favori->getId())) {
    ?>

<div class="not-liked" data-idFiche="<?= $this->getId() ?>"></div>

<?php
} else {
    ?>

<div class="liked" data-id="<?= $favori->getId() ?>"></div>

<?php
}