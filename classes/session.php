<?php

// Classe statique, gère le mécanisme de session (récupère les informations stockées dans $_SESSION, vérifie les informations de saisies de l'utilisateur lors de la connexion)

class session {
    
    
    /********* ATTRIBUTS *********/
    protected static $user;
    protected static $userId;
    protected static $connected;
    
    
    /********* INITIALISATION *********/
    public static function init() {
        // Rôle : initialise l'objet session
        // Retour : aucun
        // Paramètre : aucun
        
        self::$userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : 0;
        self::$connected = isset($_SESSION["connected"]) ? $_SESSION["connected"] : false;
        
        includeClass("model_user");
        self::$user = new model_user(self::getUserId());
    }
    
    
    /********* GETTERS *********/
    public static function getUser() {
        // Rôle : retourner la valeur de $user
        // Retour : la valeur de $user
        // Paramètre : aucun
        
        if (isset(self::$user)) {
            return self::$user;
        } else {
            return new model_user();
        }
    }
    
    public static function getUserId() {
        // Rôle : retourner la valeur de userId
        // Retour : la valeur de userId
        // Paramètre : aucun
        
        if (isset(self::$userId)) {
            return self::$userId;
        } else {
            return 0;
        }
    }
    
    public static function getConnected() {
        // Rôle : retourner la valeur de connected
        // Retour : la valeur de connected
        // Paramètre : aucun
        
        if (isset(self::$connected)) {
            return self::$connected;
        } else {
            return false;
        }
    }
    
    
    /********* SETTERS *********/
    public static function setUser($user) {
        // Rôle : modifier la valeur de user
        // Retour : true / false
        // Paramètre : 
        //          - $user : objet model_user (ou id)
        
        if (!is_object($user)) {
            $user = new model_user($user);
        }
        
        if (get_class($user) !== "model_user") {
            debug("session::setUser() : l'objet passé en paramètre n'est pas de la bonne classe");
            return false;
        }
        
        self::$user = $user;
        
        return true;
    }
    
    public static function setUserId($userId) {
        // Rôle : modifier la valeur de userId
        // Retour : true / false
        // Paramètre :
        //          - $userId : id de l'utilisateur connecté
        
        if (empty($userId)) {
            debug("session::setUserId() : l'id est vide");
            return false;
        }
        
        self::$userId = $userId;
        $_SESSION["userId"] = $userId;
        
        return true;
    }
    
    public static function setConnected($valeur) {
        // Rôle : modifier la valeur de connected
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur de l'attribut
        
        if ($valeur) {
            $_SESSION["connected"] = true;
            self::$connected = true;
            return true;
        } elseif (!$valeur) {
            $_SESSION["connected"] = false;
            self::$connected = false;
            return true;
        } else {
            return false;
        }
    }
    
    
    /********* RELATIONS BASE DE DONNEES *********/
    public static function verifyLogin($login, $password) {
        // Rôle : vérifier les données saisies par l'utilisateur lors de la connexion
        // Retour : true / false
        // Paramètre : 
        //          - $login: login utilisateur
        //          - $password : mot de passe utilisateur
        
        // Vérification des paramètres
        if (empty($login) or empty($password)) {
            debug("session::verifyLogin() : l'un des paramètres est vide");
            return false;
        }
        
        // Construction de la requête SQL
        $sql = "SELECT * FROM `".self::getUser()->getTable()."` WHERE `mail` = :mail;";
        $param = [":mail" => $login];
        
        // Préparation de la requête
        $req = model::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug("session::verifyLogin() : échec de la requête $sql");
            return false;
        }
        
        // Vérification du résultat de la requête
        if ($req->rowCount() !== 1) {
            debug("session::verifyLogin() : aucune entrée, ou plus d'une entrée, récupérée");
            return false;
        }
        
        // Vérification du mot de passe
        $ligne = $req->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($password, $ligne["mdp"])) {
            debug("session::verifyLogin() : mot de passe incorrect");
            return false;
        } else {
            self::setUser($ligne);
            return true;
        }
    }
    
    
    /********* CONNEXION / DECONNEXION *********/
    public static function connect() {
        // Rôle : connecter l'utilisateur courant
        // Retour : aucun
        // Paramètre : aucun
        
        $_SESSION["connected"] = true;
        self::setConnected($_SESSION["connected"]);
        $_SESSION["userId"] = self::getUser()->getId();
        self::setUserId($_SESSION["userId"]);
    }
    
    public static function disconnect() {
        // Rôle : déconnecter l'utilisateur courant
        // Retour : aucun
        // Paramètre : aucun
        
        $_SESSION["connected"] = false;
        self::setConnected($_SESSION["connected"]);
    }
}
