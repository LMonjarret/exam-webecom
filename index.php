<?php

// Routeur de l'application "Mes fiches jardinage"


// Initialisation de l'appli
include_once "lib/init.php";

// Récupération des paramètres
$module = isset($_GET["module"]) ? $_GET["module"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : "";

// Création de l'objet
$classe = "model_$module";

if (!includeClass($classe)) {
    // On affiche l'accueil
    include "views/pages/accueil.php";
    exit;
}

$objet = new $classe($id);

if ($module !== "user" and !session::getConnected()) {
    include "views/pages/souscription.php";
    exit;
}

// Appel du contrôleur correspondant à l'action
$controller = "action_$action";

if (!method_exists($objet, $controller)) {
    // On affiche l'accueil
    include "views/pages/accueil.php";
} else {
    // Vérification de la connexion
    if ($module !== "user") {
        if (!session::getConnected()) {
            // On affiche la page d'accueil
            include "views/pages/accueil.php";
            exit;
        }
    }
    
    // Appel du contrôleur
    $objet->$controller();
}