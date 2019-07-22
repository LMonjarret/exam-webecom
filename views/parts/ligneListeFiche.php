<?php

// Template de type part, affiche une ligne d'un tableau contenant diverses informations d'une fiche
// A besoin de :
//          - $liste, la liste contenant les fiches

foreach ($liste as $fiche) {
    
    $resume = str_split($fiche->html("texte"), 20);
    
    ?>

<tr>
    <td><?= $fiche->html("titre") ?></td>
    <td><?= $resume[0] ?></td>
    <td><a href="index.php?module=user&action=profil&id=<?= $fiche->get("auteur")->html("id") ?>"><?= $fiche->get("auteur")->html("nom")." ".$fiche->get("auteur")->html("prenom") ?></a></td>
    <td><a href="index.php?module=fiche&action=detail&id=<?= $fiche->html($fiche->getPrimaryKey()) ?>">Lire la suite</a></td>
</tr>

<?php
}