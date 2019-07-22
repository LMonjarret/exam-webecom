<?php

// Décrit les différents fonctions de l'application "Mes fiches jardinage"

$debug = true;
$listeErreur = [];

function debug($message) {
    // Rôle : ajouter un message d'erreur dans le tableau global des erreurs
    // Retour : aucun
    // Paramètre : 
    //          - $message : message d'erreur à ajouter
    
    
    // Récupération des variables globales
    global $debug;
    global $listeErreur;
    
    if ($debug) {
        $listeErreur[] = $message;
    }
}

function afficheDebug() {
    // Rôle : construire une div contenant tous les messages d'erreurs
    // Retour : la div contenant tous les messages d'erreurs
    // Paramètre : aucun
    
    
    // Récupération du tableau d'erreur
    global $listeErreur;
    
    // Construction de la div que l'on affichera
    $erreurs = "<div class='error'>";
    
    foreach ($listeErreur as $erreur) {
        $erreurs .= "<p>$erreur</p>";
    }
    
    $erreurs .= "</div>";
    
    return $erreurs;
}

function includeClass($classe) {
    // Rôle : inclure une classe métier
    // Retour : true / false
    // Paramètre :
    //          - $classe : nom de la classe (sans model_)
    
    if (!class_exists($classe)) {
        if (file_exists("models/$classe.php")) {
            include_once "models/$classe.php";
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function captcha() {
    // Rôle : Interroge l'api de google pour savoir si la saisie a été faite par un humain ou un robot
    // Retour : true / false
    // Paramètre : aucun
    
    // Init de l'api
    $api = curl_init("https://www.google.com/recaptcha/api/siteverify");
    
    // Paramètres de l'api
    curl_setopt($api,CURLOPT_POST, true);
    curl_setopt($api,CURLOPT_RETURNTRANSFER, true);
    
    // Création du tableau à envoyer à l'api
    $postFields = ["secret" => "6LcUVq4UAAAAAKEzUHQhlKCpgj9hN52pf4tNbZ4n", "response" => $_POST["g-recaptcha-response"]];
    
    // Envoie du tableau
    curl_setopt($api,CURLOPT_POSTFIELDS, $postFields);
    
    // Récupération de la réponse
    $reponse = json_decode(curl_exec($api), true);
    
    // Test de la réponse
    if ($reponse["success"]) {
        return true;
    } else {
        return false;
    }
}