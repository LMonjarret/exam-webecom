<?php

// Classe modèle, les classes métiers héritent de celle-ci

class model {
    
    
    /********* ATTRIBUTS *********/
    protected $table;
    protected $primaryKey;
    protected $champs = [];
    protected static $bdd;
    
    
    /********* METHODES MAGIQUES *********/
    public function __construct($data = "") {
        // Rôle : constructeur de l'objet, charge un nouvel objet à partir d'un paramètre si il existe
        // Retour : aucun
        // Paramètre :
        //          - $data : soit un id, soit un tableau indexé (champs => valeur)
        
        
        // Initialisation de l'objet
        $this->initFields();
        
        
        // Chargement de l'objet
        if (!empty($data)) {
            if (is_array($data)) {
                $this->loadFromArray($data);
            } else {
                $this->loadById($data);
            }
        }
    }
    
    
    /********* GETTERS *********/
    public function getTable() {
        // Rôle : retourner la valeur de l'attribut "table"
        // Retour : la valeur de l'attribut "table"
        // Paramètre : aucun
        
        if (isset($this->table)) {
            return $this->table;
        } else {
            return "";
        }
    }
    
    public function getPrimaryKey() {
        // Rôle : retourner la valeur de l'attribut "primaryKey"
        // Retour : la valeur de l'attribut "primaryKey"
        // Paramètre : aucun
        
        if (isset($this->primaryKey)) {
            return $this->primaryKey;
        } else {
            return "";
        }
    }
    
    public function getId() {
        // Rôle : retourner l'id de l'objet courant
        // Retour : l'id de l'objet courant
        // Paramètre : aucun
        
        return $this->getChamps($this->getPrimaryKey())->getValue();
    }
    
    public function getChamps($champs = "") {
        // Rôle : retourner le champs passé en paramètre, ou tous si il n'y en a pas
        // Retour : un champs ou une liste de champs
        // Paramètre :
        //          - $champs : nom du champs que l'on veut récupérer (optionnel)
        
        if (!empty($champs)) {
            if (array_key_exists($champs, $this->champs)) {
                return $this->champs[$champs];
            } else {
                debug(get_class($this)."->getChamps() : le champs $champs n'existe pas");
                return [];
            }
        } else {
            return $this->champs;
        }
    }
    
    public static function getBdd() {
        // Rôle : retourner l'ouverture de la base de données
        // Retour : un objet PDO
        // Paramètre : aucun
        
        if (!isset(self::$bdd)) {
            
            try {
                self::$bdd = new PDO("mysql:host=sqlprive-be24678-001.privatesql;dbname=montjarret;charset=UTF8", "montjarret", "Night42000");
            } catch (Exception $ex) {
                debug(get_class($this)."getBdd() : échec de la connexion à la base de données, message d'erreur : $ex");
            }
        }
        
        return self::$bdd;
    }
    
    public function get($champs) {
        // Rôle : retourner le contenu de l'attribut "value" du champs passé en paramètre
        // Retour : le contenu de l'attribut "value" du champs passé en paramètre
        // Paramètre : 
        //          - $champs : nom du champs dont on veut récupérer le contenu de l'attribut "value"
        
        if (array_key_exists($champs, $this->getChamps())) {
            return $this->getChamps($champs)->get();
        } else {
            debug(get_class($this)."->get() : le champs $champs n'existe pas");
            return "";
        }
    }
    
    public function getValue($champs) {
        // Rôle : retourner la valeur du champs passé en paramètre
        // Retour : la valeur du champs passé en paramètre
        // Paramètre :
        //          - $champs : nom du champs dont on veut récupérer la valeur
        
        if (array_key_exists($champs, $this->getChamps())) {
            return $this->getChamps($champs)->getValue();
        } else {
            debug(get_class($this)."->getValue() : le champs $champs n'existe pas");
            return "";
        }
    }
    
    public function getAttribut($champs, $attribut) {
        // Rôle : retourner la valeur d'un attribut d'un champs
        // Retour : valeur d'un attribut d'un champs
        // Paramètres : 
        //          - $champs : nom du champs
        //          - $attribut : nom de l'attribut
    }
    
    public function html($champs) {
        // Rôle : retourner la valeur du champs passé en paramètre sous un format adapté à l'affiche
        // Retour : la valeur du champs passé en paramètre sous un format adapté à l'affiche
        // Paramètre : 
        //          - $champs : nom du champs
        
        if (array_key_exists($champs, $this->getChamps())) {
            return $this->getChamps($champs)->html();
        } else {
            debug(get_class($this)."->html() : le champs $champs n'existe pas");
            return "";
        }
    }
    
    
    /********* SETTERS *********/
    public function set($champs, $valeur) {
        // Rôle : modifier la valeur d'un champs
        // Retour : true / false
        // Paramètre :
        //          - $champs : nom du champs dont on veut modifier la valeur
        //          - $valeur : nouvelle valeur du champs
        
        if (array_key_exists($champs, $this->getChamps())) {
            return $this->getChamps($champs)->set($valeur);
        } else {
            debug(get_class($this)."->set() : le champs $champs n'existe pas");
            return false;
        }
    }
    
    
    /********* RELATIONS BASE DE DONNEES *********/
    public function insert() {
        // Rôle : insérer l'objet courant dans la base de données
        // Retour : true / false
        // Paramètre : aucun
        
        // Vérification de l'id
        if (!empty($this->getId())) {
            debug(get_class($this)."->insert() : l'objet courant a déjà un id");
            return false;
        }
        
        // Construction de la requête
        $sql = "INSERT INTO `".$this->getTable()."` SET ";
        $param = [];
        $start = true;
        
        foreach ($this->getChamps() as $nom => $champs) {
            if ($nom === $this->getPrimaryKey() or !$champs->getAttribut("inBdd")) {
                continue;
            }
            if ($start) {
                $sql .= "`$nom` = :$nom";
                $start = false;
            } else {
                $sql .= ", `$nom` = :$nom";
            }
            
            $param[":$nom"] = $champs->getValue();
        }
        
        $sql .= ";";
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->insert() : échec de la requête $sql");
            return false;
        }
        
        // Assignation de l'id
        if ($req->rowCount() === 1) {
            $this->set($this->getPrimaryKey(), self::getBdd()->lastInsertId());
            return true;
        } else {
            debug(get_class($this)."->insert() : aucune entrée, ou bien plus d'une entrée, créée");
            return false;
        }
    }
    
    public function update() {
        // Rôle : met à jour l'objet courant dans la base de données
        // Retour : true / false
        // Paramètre : aucun
        
        // Vérification de l'id
        if (empty($this->getId())) {
            debug(get_class($this)."->update() : l'objet courant n'a pas d'id");
            return false;
        }
        
        // Construction de la requête
        $sql = "UPDATE `".$this->getTable()."` SET ";
        $param = [];
        $start = true;
        
        foreach ($this->getChamps() as $nom => $champs) {
            if ($nom === $this->getPrimaryKey() or !$champs->getAttribut("inBdd")) {
                continue;
            }
            if ($start) {
                $sql .= "`$nom` = :$nom";
                $start = false;
            } else {
                $sql .= ", `$nom` = :$nom";
            }
            
            $param[":$nom"] = $champs->getValue();
        }
        
        $sql .= " WHERE `".$this->getPrimaryKey()."` = :id;";
        $param[":id"] = $this->getId();
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->update() : échec de la requête $sql");
            return false;
        }
        
        return true;
    }
    
    public function liste() {
        // Rôle : retourner une liste d'objet
        // Retour : une liste d'objet
        // Paramètre : aucun
        
        // Construction de la requête
        $sql = "SELECT * FROM `".$this->getTable()."` WHERE 1;";
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute()) {
            debug(get_class($this)."->liste() : échec de la requête $sql");
            return [];
        }
        
        // Récupération des valeurs
        $classe = get_class($this);
        $liste = [];
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne[$this->getPrimaryKey()]);
        }
        
        // Retour de la liste
        return $liste;
    }
    
    
    /********* CHARGEMENT DE L'OBJET *********/
    public function loadById($id) {
        // Rôle : charger l'objet courant depuis la base de données
        // Retour : true / false
        // Paramètre : aucun
        
        // Vérification de l'id
        if (empty($id)) {
            debug(get_class($this)."->loadById() : l'id passé en paramètre est vide");
            return false;
        }
        
        // Construction de la requête
        $sql = "SELECT * FROM `".$this->getTable()."` WHERE `".$this->getPrimaryKey()."` = :id;";
        $param = [":id" => $id];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->loadById() : échec de la requête $sql");
            return false;
        }
        
        // Récupération du résultat
        if ($req->rowCount() !== 1) {
            debug(get_class($this)."->loadById() : aucun, ou plus d'un résultat, récupéré");
            return false;
        } else {
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
        }
        
        // Chargement de l'objet
        return $this->loadFromArray($ligne);
    }
    
    public function loadFromArray($array) {
        // Rôle : charger l'objet courant depuis un tableau
        // Retour : true / false
        // Paramètre : 
        //          - $array : tableau contenant les données de l'objet
        
        // Vérification du paramètre
        if (!is_array($array)) {
            debug(get_class($this)."->loadFromArray() : le paramètre passé n'est pas un tableau");
            return false;
        }
        
        // Chargement de l'objet
        foreach ($array as $nom => $valeur) {
            if (!array_key_exists($nom, $this->getChamps())) {
                continue;
            }
            if ($this->getAttribut($nom, "type") === "LINK") {
                $classe = "model_".$this->getAttribut($nom, "lien");
                includeClass($classe);
                $valeur = new $classe($valeur);
            }
            
            $this->set($nom, $valeur);
        }
        
        return true;
    }
    
    
    /********* INITIALISATION DE L'OBJET *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant (surchargé par la classe fille)
        // Retour : aucun
        // Paramètre : aucun
    }
    
    protected function makeFieldInt($nom) {
        // Rôle : créer un champs de type INT dans l'objet courant
        // Retour : aucun
        // Paramètre :
        //          - $nom : nom du champs
        
        include_once "classes/fieldInt.php";
        
        $this->champs[$nom] = new fieldInt($nom, $this);
    }
    
    protected function makeFieldStr($nom) {
        // Rôle : créer un champs de type STRING dans l'objet courant
        // Retour : aucun
        // Paramètre :
        //          - $nom : nom du champs
        
        include_once "classes/fieldStr.php";
        
        $this->champs[$nom] = new fieldStr($nom, $this);
    }
    
    protected function makeFieldLink($nom, $affichage, $lien = "") {
        // Rôle : créer un champs de type LINK dans l'objet courant
        // Retour : aucun
        // Paramètres :
        //          - $nom : nom du champs
        //          - $affichage : nom du champs qui sert pour l'affichage
        //          - $lien : nom de la table liée
        
        if (empty($lien)) {
            $lien = $nom;
        }
        
        include_once "classes/fieldLink.php";
        
        $this->champs[$nom] = new fieldLink($nom, $affichage, $lien, $this);
    }
    
    protected function makeFieldDate($nom) {
        // Rôle : créer un champs de type DATE dans l'objet courant
        // Retour : aucun
        // Paramètre :
        //          - $nom : nom du champs
        
        include_once "classes/fieldDate.php";
        
        $this->champs[$nom] = new fieldDate($nom, $this);
    }
    
    
    /********* CREATION INPUT *********/
    public function makeSelect($champs) {
        // Rôle : construire un select pour un champs de type lien en fonction des données de la base de données
        // Retour : une chaîne de caractère équivalente à un select
        // Paramètre : 
        //          - $champs : nom du champs dont on veut faire un select
        
        $select = $this->getChamps($champs)->makeSelect();
        
        return $select;
    }
}
