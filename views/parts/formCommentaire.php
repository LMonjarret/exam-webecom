<?php

// Template de type part, affiche un formulaire de création de commentaire

?>

<form method="POST" action="index.php?module=commentaire&action=insert&idFiche=<?= $this->getId() ?>" class="createCommentaire">
    <label for="commentaire">Votre commentaire :</label>
    <textarea name="texte" id="commentaire"></textarea>
    <input type="submit" value="Commenter" />
</form>