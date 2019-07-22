<?php

// Template de type part, affiche un select si l'utilisateur connecté est un administrateur

if (session::getUser()->getValue("profil") === "ADM" and $this->getValue("profil") !== "ADM") {
    ?>

<p>Profil : <?= $this->makeSelect("profil") ?></p>

<?php
}