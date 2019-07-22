<?php

// Template de type part, affiche une ligne d'un tableau contenant diverses informations d'un utilisateur
// A besoin de :
//          - $liste, la liste contenant les fiches

foreach ($liste as $user) {
    ?>

<tr>
    <td><?= ucfirst($user->html("nom")) ?></td>
    <td><?= ucfirst($user->html("prenom")) ?></td>
    <td><a href="index.php?module=user&action=profil&id=<?= $user->getId() ?>">Voir le profil</a></td>
</tr>

<?php
}