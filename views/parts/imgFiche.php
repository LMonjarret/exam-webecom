<?php

// Template de type part, affiche une balise image si la fiche a une image

if (!empty($this->getValue("image"))) {
    ?>

<img src="<?= $this->getValue("image") ?>" />

<?php
}