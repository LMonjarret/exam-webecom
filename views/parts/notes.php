<?php

if ($this->verifyNote()) {
    ?>

<p>Note moyenne de la fiche : <?= $this->html("note") ?></p>

<?php
} else {
    ?>

<div class="stars" data-idFiche="<?= $this->getId() ?>" >
    <p>Attribuer une note à cette fiche !</p>
    <div class="star"></div><div class="star"></div><div class="star"></div><div class="star"></div><div class="star"></div>
</div>

<?php
}