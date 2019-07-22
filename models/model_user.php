<?php

// Classe d'un objet métier, hérite de model. Décrit un compte utilisateur

class model_user extends model{
    
    
    /********* ATTRIBUTS *********/
    protected $table = "user";
    protected $primaryKey = "id";
    
    
    /********* INITIALISATION *********/
    public function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldInt("id");
        $this->makeFieldStr("nom");
        $this->makeFieldStr("prenom");
        $this->makeFieldStr("mail");
        $this->makeFieldStr("mdp");
        $this->makeFieldLink("profil", "libelle");
        $this->makeFieldDate("abonnement");
        $this->makeFieldInt("actif");
    }
    
    
    /********* RELATIONS BDD *********/
    public function verifyUniqueMail($mail) {
        // Rôle : vérifier dans la base de données si le mail donné en paramètre existe
        // Retour : true / false
        // Paramètre :
        //          - $mail: l'adresse mail a vérifié
        
        // Construction de la requête
        $sql = "SELECT * FROM `user` WHERE `mail` = :mail;";
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        $param = [":mail" => $mail];
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->verifyUniqueMail() : échec de la requête $sql");
            return false;
        }
        
        // Récupération du résultat
        if ($req->rowCount() !== 0) {
            return false;
        } else {
            return true;
        }
    }
    
    
    /********* CHARGEMENT DE L'OBJET *********/
    public function defaut() {
        // Rôle : assigne des valeurs par défaut à l'objet courant
        // Retour : aucun
        // Paramètre : aucun
        
        $this->set("nom", "anonyme");
        $this->set("profil", "ABO");
        $this->set("abonnement", $this->get("abonnement")->add(new DateInterval("P365D")));
        $this->set("actif", true);
    }
    
    
    /********* CONTROLEURS *********/
    public function action_form() {
        // Rôle : contrôleur de l'action form, affiche un formulaire en fonction d'un mode
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération du mode
        $mode = isset($_GET["mode"]) ? $_GET["mode"] : "";
        
        // Affichage d'un formulaire en fonction du mode
        if ($mode === "insert") {
            include "views/pages/souscription.php";
            
        } elseif ($mode === "connect") {
            include "views/pages/connexion.php";
            
        } elseif ($mode === "update") {
            
            // Vérification de la connexion et de l'identité de l'utilisateur
            if (!session::getConnected()) {
                include "views/pages/accueil.php";
                exit;
            }
            if (session::getUserId() !== $this->getId() and session::getUser()->getValue("profil") !== "ADM") {
                // On affiche l'accueil
                include "views/pages/accueil.php";
                exit;
            }
            
            // On inclut le formulaire de modification
            include "views/pages/modificationUser.php";
        }
    }
    
    public function action_insert() {
        // Rôle : contrôleur de l'action insert, créant un compte utilisateur dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de la connexion de l'utilisateur
        if (session::getConnected()) {
            // On redirige sur le profil de l'utilisateur
            header("Location: index.php?module=user&action=profil&id=".session::getUserId());
            exit;
        }
        
        // Vérification du reCaptcha
        if (!captcha()) {
            header("Location: index.php");
            exit;
        }
        
        // On vérifie les informations du $_POST
        foreach ($_POST as $key => $value) {
            if (empty($value)) {
                include "views/pages/souscription.php";
                exit;
            }
        }
        
        // Chargement de l'objet
        if ($_POST["membre"] === "oui") {
            if (!session::verifyLogin($_POST["mail"], $_POST["mdp"])) {
                include "views/pages/souscription.php";
                exit;
            } else {
                $_SESSION["userId"] = session::getUser()->getId();
                header("Location: index.php?module=user&action=renouveller&id=".$_SESSION["userId"]);
                exit;
            }
        } else {
            $this->loadFromArray($_POST);
        }
        
        // Attribution des valeurs par défaut
        $this->defaut();
        
        // Vérification de l'adresse mail unique
        if (!$this->verifyUniqueMail($_POST["mail"])) {
            include "views/pages/souscription.php";
            exit;
        }
        
        // Hachage du mot de passe
        $this->set("mdp", password_hash($_POST["mdp"], PASSWORD_DEFAULT));
        
        // Création dans la base de données
        if ($this->insert()) {
            session::setUser($this);
            session::connect();
            include "views/pages/accueil.php";
        } else {
            debug(get_class($this)."->action_insert() : échec de l'insertion dans la base de données");
            include "views/pages/souscription.php";
        }
    }
    
    public function action_update() {
        // Rôle : contrôleur de l'action update, mettant à jour l'utilisateur courant dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de l'utilisateur
        if (session::getUserId() !== $this->getId() and session::getUser()->getValue("profil") !== "ADM") {
            include "views/pages/accueil.php";
            exit;
        }
        
        // Remplacement des valeurs
        foreach ($_POST as $key => $value) {
            if ($key !== "mdp" and !empty($value)) {
                $this->set($key, $value);
            } elseif ($key === "mdp" and !empty($value)) {
                $this->set("mdp", password_hash($value, PASSWORD_DEFAULT));
            }
        }
        
        // Mis à jour des informations utilisateurs
        if ($this->update()) {
            header("Location: index.php?module=user&action=profil&id=".session::getUserId());
            exit;
        } else {
            include "views/pages/modificationUser.php";
        }
    }
    
    public function action_renouveller() {
        // Rôle : contrôleur de l'action renouveller, ajoutant du temps à l'abonnement de l'utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de l'utilisateur
        if (session::getUserId() !== $this->getId()) {
            include "views/pages/accueil.php";
            exit;
        }
        
        // Ajout du temps d'abonnement (normalement après confirmation du paiement)
        $now = new DateTime();
        if ($this->get("abonnement") < $now) {
            $this->set("abonnement", $now);
        }
        
        $this->set("abonnement", $this->get("abonnement")->add(new DateInterval("P365D")));
        
        // Mis à jour de l'utilisateur dans la base de données
        if ($this->update()) {
            if (!session::getConnected()) {
                session::connect();
            }
            header("Location: index.php?module=user&action=profil&id=".session::getUserId());
        } else {
            include "views/pages/accueil.php";
        }
    }
    
    public function action_deactivate() {
        // Rôle : contrôleur de l'action deactivate, désactivant un compte utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        if (!session::getUser()->getValue("profil") === "ADM" or !session::getConnected()) {
            include "views/pages/accueil.php";
            exit;
        }
        
        $this->set("actif", false);
        $this->update();
        
        include "views/pages/accueil.php";
    }
    
    public function action_activate() {
        // Rôle : contrôleur de l'action activate, activant un compte utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        if (!session::getUser()->getValue("profil") === "ADM" or !session::getConnected()) {
            include "views/pages/accueil.php";
            exit;
        }
        
        $this->set("actif", true);
        $this->update();
        
        include "views/pages/accueil.php";
    }
    
    public function action_profil() {
        // Rôle : contrôleur de l'action profil, affichant le profil d'un utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de la connexion
        if (!session::getConnected()) {
            include "views/pages/connexion.php";
            exit;
        }
        
        // Affichage du profil
        include "views/pages/profil.php";
    }
    
    public function action_connect() {
        // Rôle : contrôleur de l'action connect, connecte un utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de la connexion
        if (session::getConnected()) {
            // On redirige l'utilisateur sur son profil
            header("Location: index.php?module=user&action=profil&id=".session::getUserId());
            exit;
        }
        
        // Vérification du captcha
        if (!captcha()) {
            header("Location: index.php");
            exit;
        }
        
        // Vérification des données saisies par l'utilisateur
        foreach ($_POST as $key=>$value) {
            if (empty($value)) {
                // On réaffiche le formulaire de connexion
                include "views/pages/connexion.php";
                exit;
            }
        }
        
        // Vérification des données saisies par l'utilisateur
        if (!session::verifyLogin($_POST["mail"], $_POST["mdp"])) {
            // On réaffiche le formulaire
            include "views/pages/connexion.php";
            exit;
        }
        
        // Connexion de l'utilisateur
        $now = new DateTime();
        
        if (!session::getUser()->getValue("actif")) {
            // Compte désactivé, on renvoie sur la page d'accueil
            include "views/pages/accueil.php";
        } elseif (session::getUser()->get("abonnement") < $now) {
            // Plus d'abonnement, on renvoie sur la page de souscription
            include "views/pages/souscription.php";
        } else {
            session::connect();
            include "views/pages/accueil.php";
        }
    }
    
    public function action_disconnect() {
        // Rôle : contrôleur de l'action disconnect, déconnecte l'utilisateur connecté
        // Retour : aucun
        // Paramètre : aucun
        
        if (!session::getConnected()) {
            include "views/pages/accueil.php";
            exit;
        }
        
        session::disconnect();
        include "views/pages/accueil.php";
    }
    
    public function action_modifProfil() {
        // Rôle : contrôleur de l'action modifProfil, qui change le profil d'un utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification de l'utilisateur
        if (!session::getConnected()) {
            header("Location: index.php");
            exit;
        }
        
        if (session::getUser()->getValue("profil") !== "ADM" or $this->getValue("profil") === "ADM") {
            exit;
        }
        
        // Récupération des paramètres
        $code = isset($_POST["code"]) ? $_POST["code"] : "";
        
        // Modification du profil
        $this->set("profil", $code);
        $this->update();
        
        // Envoie du profil pour affichage
        $dateJour = new DateTime();
        if ($this->get("abonnement") < $dateJour and $this->getValue("profil") !== "EXP") {
            $profil = htmlentities("ancien abonné");
        } else {
            $profil = $this->html("profil");
        }
        
        header('Content-Type: application/json');
        echo json_encode($profil);
    }
    
    public function action_liste() {
        // Rôle : afficher une liste des utilisateurs
        // Retour : aucun
        // Paramètres : aucun
        
        // Vérification de la connexion
        if (!session::getConnected()) {
            header("Location: index.php?module=user&action=connect");
            exit;
        }
        
        // Création de la liste
        $liste = $this->liste();
        
        // include du template
        include "views/pages/userListe.php";
    }
}
