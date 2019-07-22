<?php

// Classe d'un objet métier, décrit un favoris (ici une fiche jardinage) d'un utilisateur. Hérite de "model"

class model_favori extends model {
    
    
    /********* ATTRIBUTS *********/
    protected $table = "favori";
    protected $primaryKey = "id";
    
    
    /********* RELATION BDD *********/
    public function delete() {
        // Rôle : supprime un favori de la base de données
        // Retour : true / false
        // Paramètre : aucun
        
        // Vérification de l'id
        if (empty($this->getId())) {
            debug(get_class($this)."->delete() : l'objet n'a pas d'id");
            return false;
        }
        
        // Construction de la requête
        $sql = "DELETE FROM `favori` WHERE `id` = :id;";
        $param = [":id" => $this->getId()];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->delete() : échec de la requête $sql");
            return false;
        }
        
        // Vérification de la requête
        if ($req->rowCount() !== 1) {
            debug(get_class($this)."->delete() : aucune ligne touchée (ou plus d'une ligne touchée)");
            return false;
        } else {
            return true;
        }
    }
    
    public function load($idFiche) {
        // Rôle : charge un objet favori en fonction de l'utilisateur et de la fiche consultée
        // Retour : l'objet courant
        // Paramètre :
        //          - $idFiche : id de la fiche consultée
        
        // Vérification
        if (!empty($this->getId())) {
            debug(get_class($this)."->load() : l'objet a déjà un id");
            return $this;
        }
        
        if (empty($idFiche)) {
            debug(get_class($this)."->load() : l'un des paramètres est vide");
            return $this;
        }
        
        // Construction de la requête
        $sql = "SELECT * FROM `favori` WHERE `user` = :user AND `fiche` = :fiche;";
        $param = [":user" => session::getUserId(), ":fiche" => $idFiche];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->load() : échec de la requête $sql");
            return $this;
        }
        
        // Récupération du résultat
        if ($req->rowCount() === 1) {
            $this->loadFromArray($req->fetch(PDO::FETCH_ASSOC));
        }
        
        return $this;
    }
    
    
    /********* INITIALISATION *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant (surchargé par la classe fille)
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldInt("id");
        $this->makeFieldLink("user", "nom");
        $this->makeFieldLink("fiche", "titre");
    }
    
    
    /********* CONTROLEURS *********/
    public function action_insert() {
        // Rôle : contrôleur de l'action insert, ajoutant un favori dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération du paramètre
        $idFiche = isset($_POST["idFiche"]) ? $_POST["idFiche"] : "";
        
        // Vérification
        if (empty($idFiche)) {
            exit;
        }
        
        // Chargement de l'objet
        $this->set("user", session::getUserId());
        $this->set("fiche", $idFiche);
        
        // Insertion dans la base de données
        if ($this->insert()) {
            $data= ["idFavori" =>$this->getId()];
            
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
    
    public function action_delete() {
        // Rôle : contrôleur de l'action delete, enlevant un favori de la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification
        if (session::getUserId() !== $this->getValue("user")) {
            include "views/pages/accueil.php";
            exit;
        }
        
        if (empty($this->getId())) {
            exit;
        }
        
        $data = ["idFiche" => $this->getValue("fiche")];
        
        if ($this->delete()) {
            
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}
