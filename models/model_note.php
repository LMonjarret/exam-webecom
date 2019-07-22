<?php

// Classe d'un objet métier, décrit la note d'une fiche donnée par un utilisateur. Hérite de "model"

class model_note extends model {
    
    
    /********* ATTRIBUTS *********/
    protected $table = "note";
    protected $primaryKey = "id";
    
    
    /********* INITIALISATION *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant (surchargé par la classe fille)
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldInt("id");
        $this->makeFieldLink("user", "nom");
        $this->makeFieldLink("fiche", "titre");
        $this->makeFieldInt("note");
    }
    
    
    /********* ACTIONS *********/
    public function action_insert() {
        // Rôle : contrôleur de l'action insert, ajoute une note dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération des paramètres
        $note = isset($_POST["note"]) ? $_POST["note"] : 0;
        $idFiche = isset($_POST["idFiche"]) ? $_POST["idFiche"] : "";
        
        // Initialisation du tableau à renvoyer au javascript
        $data = [];
        
        // Vérification des paramètres
        if (empty($note) or empty($idFiche)) {
            $data["resultat"] = 0;
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
        
        // Chargement de l'objet
        $this->set("user", session::getUserId());
        $this->set("fiche", $idFiche);
        $this->set("note", $note);
        
        // Insert de l'objet
        if (!$this->insert()) {
            $data["resultat"] = 0;
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
        
        // Récupération de la moyenne
        $this->set("fiche", $idFiche);
        
        $moyenne = $this->get("fiche")->getValue("note");
        
        // Chargement du tableau data
        $data["resultat"] = 1;
        $data["moyenne"] = $moyenne;
        
        // Envoie des données au javascript
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
