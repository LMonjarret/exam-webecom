<?php

// Template de type part, affiche différents boutons sur la page d'une fiche en fonction du profil utilisateur


if (session::getUser()->getValue("profil") === "ADM") {
    ?>

<a href="index.php?module=fiche&action=form&mode=update&id=<?= $this->getId() ?>">Editer</a>

    <?php
} elseif (session::getUser()->getValue("profil") === "EXP") {
    if ($this->get("auteur")->getValue("profil") === "ABO" or session::getUserId() === $this->getValue("auteur")) {
        ?>

<a href="index.php?module=fiche&action=form&mode=update&id=<?= $this->getId() ?>">Editer</a>

<?php
    }
    
} elseif (session::getUser()->getValue("profil") === "ABO") {
    if ($this->get("auteur")->getId() === session::getUserId()) {
        ?>

<a href="index.php?module=fiche&action=form&mode=update&id=<?= $this->getId() ?>">Editer</a>

<?php
    }
}