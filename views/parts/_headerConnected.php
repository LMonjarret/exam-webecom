<?php

// Template de type part, affiche un header différent en fonction de l'état de connexion de l'utilisateur

if (session::getConnected()) {
    ?>

<nav>
    <a href="index.php?module=user&action=liste">Liste des membres</a>
    <a href="index.php?module=fiche&action=liste">Liste des fiches conseil</a>
</nav>
<nav>
    <a href="index.php?module=user&action=profil&id=<?= session::getUserId() ?>">Mon profil</a>
    <a href="index.php?module=user&action=disconnect">Se déconnecter</a>
</nav>

<?php
} else {
    ?>

<nav>
    <a href="index.php?module=user&action=form&mode=insert">Souscrire</a>
    <a href="index.php?module=user&action=form&mode=connect">Se connecter</a>
</nav>

<?php
}