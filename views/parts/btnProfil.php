<?php

// Template de type part, affiche des boutons sur la page du profil utilisateur
// A besoin de :
//              - $this, objet courant

if (session::getUserId() === $this->getId()) {
    ?>


<a href="index.php?module=user&action=renouveller&id=<?= session::getUserId() ?>">Renouveller mon abonnement</a>
<a href="index.php?module=user&action=form&mode=update&id=<?= session::getUserId() ?>">Modifier mon profil</a>


<?php
}

if (session::getUser()->getValue("profil") === "ADM" and $this->getId() !== session::getUserId()) {
    if ($this->getValue("actif")) {
        ?>

<a href="index.php?module=user&action=deactivate&id=<?= $this->getId() ?>">Désactiver ce compte utilisateur</a>

<?php
    } else {
    ?>

<a href="index.php?module=user&action=activate&id=<?= $this->getId() ?>">Activer ce compte utilisateur</a>

<?php
    }
}