<?php

// CLasse décrivant un champs lié à une autre table dans la base de données, hérite de la classe field

class fieldLink extends field {
    
    
    /********* ATTRIBUTS *********/
    protected $attr_type = "LINK";
    protected $attr_lien;
    protected $attr_affichage;
    
    
    /********* METHODES MAGIQUES *********/
    public function __construct($nom, $affichage, $lien, $objet) {
        // Rôle : constructeur de l'objet
        // Retour : aucun
        // Paramètres :
        //          - $nom : nom du champs
        //          - $lien : nom de la table liée
        //          - $objet : objet auquel le champs appartient
        
        $this->attr_lien = $lien;
        $this->attr_affichage = $affichage;
        
        parent::__construct($nom, $objet);
    }
    
    
    /********* GETTERS *********/
    public function get() {
        // Rôle : retourne le contenu de l'attribut valeur
        // Retour : le contenu de l'attribut valeur
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->valeur;
        } else {
            $classe = "model_".$this->getAttribut("lien");
            includeClass($classe);
            return new $classe();
        }
    }
    
    public function getValue() {
        // Rôle : retourne la valeur du champs
        // Retour : la valeur du champs
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->get()->getId();
        } else {
            return "";
        }
    }
    
    public function html() {
        // Rôle : retourne la valeur du champs sous un format adapté pour l'affichage
        // Retour : la valeur du champs sous un format adapté pour l'affichage
        // Paramètre : aucun
        
        return $this->get()->html($this->getAttribut("affichage"));
    }
    
    
    /********* SETTERS *********/
    public function set($valeur) {
        // Rôle : modifier la valeur du champs
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur du champs
        
        // Vérification du paramètre
        if (!is_object($valeur)) {
            $classe = "model_".$this->getAttribut("lien");
            includeClass($classe);
            $valeur = new $classe($valeur);
        }
        
        if (get_class($valeur) !== "model_".$this->getAttribut("lien")) {
            debug(get_class($this)."->set() : l'objet passé en paramètre n'est pas de la bonne classe");
            return false;
        }
        
        return parent::set($valeur);
    }
    
    
    /********* CREATION SELECT *********/
    public function makeSelect() {
        // Rôle : créer un select du champs
        // Retour : le select
        // Paramètre : aucun
        
        $select = "<select name='".$this->nom."' class='select_".$this->nom."' >";
        
        $select .= $this->makeOptions();
        
        $select .= "</select>";
        
        return $select;
    }
    
    protected function makeOptions() {
        // Rôle : créer toutes les options du select du champs
        // Retour : toutes les options
        // Paramètre : aucun
        
        // Construction de la requête
        $sql = "SELECT * FROM `".$this->nom."` WHERE 1;";
        
        // Préparation de la requête
        $classe = get_class($this->objet);
        $bdd = $classe::getBdd();
        $req = $bdd->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute()) {
            debug(get_class($this)."->makeOptions() : échec de la requête $sql");
            return "";
        }
        
        // Récupération du résultat
        $options = "";
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            if ($ligne[$this->get()->getPrimaryKey()] === "ADM") {
                continue;
            }
            if ($this->objet->getValue($this->nom) === $ligne[$this->get()->getPrimaryKey()]) {
                $options .= "<option value='".$ligne[$this->get()->getPrimaryKey()]."' data-id='".$this->objet->getId()."' selected >";
                $options .= ucfirst(htmlentities($ligne[$this->getAttribut("affichage")]));
                $options .= "</option>";
            } else {
                $options .= "<option value='".$ligne[$this->get()->getPrimaryKey()]."' data-id='".$this->objet->getId()."' >";
                $options .= ucfirst(htmlentities($ligne[$this->getAttribut("affichage")]));
                $options .= "</option>";
            }
        }
        
        return $options;
    }
}
