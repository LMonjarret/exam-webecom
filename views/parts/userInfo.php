<?php

// Template de type part, affiche les informations privées de l'utilisateur si celui-ci consulte son propre profil
// A besoin de :
//          - $this, objet courant

if (session::getUserId() === $this->getId()) {
    ?>

<p>Adresse e-mail : <?= $this->html("mail") ?></p>
<p>Fin de l'abonnement : <?= $this->html("abonnement") ?>, soit <?= htmlentities($joursRestants) ?></p>

<?php
}