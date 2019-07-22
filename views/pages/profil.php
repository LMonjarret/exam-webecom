<?php

// Template de type page, affiche le profil d'un utilisateur
// A besoin de : 
//              - $this, l'objet courant

// Calcul du nombre de jours restants à l'abonnement
$now = new DateTime();
$decompteAbonnement = $now->diff($this->get("abonnement"));
$joursRestants = $decompteAbonnement->format("%a jour(s) restant(s)");

// Modification de l'affichage du profil si celui-ci n'est plus abonné
$dateJour = new DateTime();
if ($this->get("abonnement") < $dateJour and $this->getValue("profil") !== "EXP") {
    $profil = htmlentities("ancien abonné");
} else {
    $profil = $this->html("profil");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php include "views/parts/head.php" ?>
    </head>
    <body>
        <?php include "views/parts/header.php" ?>
        <main>
            <div>
                <h1>Profil utilisateur : <?= $this->html("nom") ?> <?= $this->html("prenom") ?></h1>
                <p>Nom : <?= ucfirst($this->html("nom")) ?></p>
                <p>Prénom : <?= ucfirst($this->html("prenom")) ?></p>
                <p class="statut">Statut : <?= $profil ?></p>
                <?php include "views/parts/userProfil.php" ?>
                <?php include "views/parts/userInfo.php" ?>
                <div>
                    <?php include "views/parts/btnProfil.php" ?>
                </div>
            </div>
        </main>
        <?php include "views/parts/footer.php" ?>
    </body>
</html>
