<?php

// Classe d'un objet métier, décrit un commentaire d'une fiche. Hérite de "model"

class model_commentaire extends model{
    
    
    /********* ATTRIBUTS *********/
    protected $table = "commentaire";
    protected $primaryKey = "id";
    
    
    /********* INITIALISATION *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant (surchargé par la classe fille)
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldStr("id");
        $this->makeFieldLink("auteur", "nom", "user");
        $this->makeFieldLink("fiche", "titre");
        $this->makeFieldDate("dt_creation");
        $this->makeFieldStr("texte");
    }
    
    
    /********* RELATIONS BDD *********/
    public function listeCommentaires($fiche) {
        // Rôle : récupère tous les commentaires d'une fiche donnée
        // Retour : une liste d'objet model_commentaire
        // Paramètre :
        //          - $fiche : id de la fiche dont on veut les commentaires
        
        if (empty($fiche)) {
            debug(get_class($this)."->listeCommentaires() : aucun id renseigné");
            return [];
        }
        
        // Construction de la requête
        $sql = "SELECT * FROM `commentaire` WHERE `fiche` = :fiche ORDER BY `dt_creation` DESC;";
        $param = [":fiche" => $fiche];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->listeCommentaires() : échec de la requête $sql");
            return [];
        }
        
        // Récupération des résultats
        $liste = [];
        $classe = get_class($this);
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne);
        }
        
        return $liste;
    }
    
    
    /********* CONTROLEURS *********/
    public function action_form() {
        // Rôle : contrôleur de l'action form, affichant un formulaire en fonction d'un mode
        // Retour : aucun
        // Paramètre : aucun
    }
    
    public function action_insert() {
        // Rôle : contrôleur de l'action insert, insérant un commentaire dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération des paramètres
        $fiche = isset($_GET["idFiche"]) ? $_GET["idFiche"] : "";
        
        if (empty($fiche)) {
            include "views/pages/accueil.php";
            exit;
        }
        
        // Chargement de l'objet
        $this->set("auteur", session::getUser());
        $this->set("fiche", $fiche);
        $this->set("dt_creation", new DateTime());
        
        // Vérification du commentaire
        if (empty($_POST["texte"])) {
            header("Location: index.php?module=fiche&action=detail&id=$fiche");
            exit;
        }
        
        // Chargement de l'objet
        $this->set("texte", $_POST["texte"]);
        
        // Insertion du commentaire dans la BDD
        if ($this->insert()) {
            header("Location: index.php?module=fiche&action=detail&id=$fiche");
        } else {
            echo "fuck it";
        }
    }
    
    public function action_update() {
        // Rôle : contrôleur de l'action update, modifiant un commentaire existant dans la base de données
        // Retour : aucun
        // Paramètre : aucun
    }
}
