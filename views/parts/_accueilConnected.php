<?php

// Template de type part, variante de la page d'accueil lorsque l'utilisateur est connecté

?>

<div class="dernieresFiches">
    <?php include "views/parts/fichesAccueil.php" ?>
</div>
<div class="recherche">
    <p>Vous souhaitez rechercher une fiche conseil ? N'allez pas plus loin !</p>
    <form method="POST" action="index.php?module=fiche&action=listeTri">
        <label for="keywords">Mots-clés / Auteur : </label>
        <input type="text" id="keywords" name="keywords" />
        <input type="submit" value="Rechercher !" />
    </form>
</div>
<div class="creer-fiche">
    <h1>Prodiguez vos conseils !</h1>
    <p>Vous aussi participez activement à notre communauté, et partagez avec nous vos connaissances !</p>
    <a href="index.php?module=fiche&action=form&mode=insert">Créer une fiche conseil</a>
</div>
<div class="fiches-utilisateur">
    <div class="mes-fiches">
        <h2>Moi aussi j'ai aidé !</h2>
        <p></p>
        <a href="index.php?module=fiche&action=own">Consulter mes fiches</a>
    </div>
    <div class="mes-favoris">
        <h3>Ceux-la, ce sont les meilleurs !</h3>
        <p></p>
        <a href="index.php?module=fiche&action=listeFavori">Voir mes favoris</a>
    </div>
</div>