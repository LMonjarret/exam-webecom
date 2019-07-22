<?php

// Classe génrale décrivant le champs d'un objet

class field {
    
    
    /********* ATTRIBUTS *********/
    protected $nom;
    protected $objet;
    protected $attr_type;
    protected $attr_inBdd = true;
    protected $valeur;
    
    
    /********* METHODES MAGIQUES *********/
    public function __construct($nom, $objet) {
        // Rôle : constructeur de l'objet
        // Retour : aucun
        // Paramètres :
        //          - $nom : nom du champs
        //          - $objet : objet auquel le champs appartient
        
        $this->nom = $nom;
        $this->objet = $objet;
    }
    
    
    /********* GETTERS *********/
    public function get() {
        // Rôle : retourne le contenu de l'attribut valeur (surchargé par la classe fille)
        // Retour : le contenu de l'attribut valeur
        // Paramètre : aucun
    }
    
    public function getValue() {
        // Rôle : retourne la valeur du champs
        // Retour : la valeur du champs
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->valeur;
        } else {
            return "";
        }
    }
    
    public function html() {
        // Rôle : retourne la valeur du champs sous un format adapté pour l'affichage
        // Retour : la valeur du champs sous un format adapté pour l'affichage
        // Paramètre : aucun
        
        return nl2br(htmlentities($this->getValue()));
    }
    
    public function getAttribut($attribut) {
        // Rôle : retourne la valeur de l'attribut passé en paramètre
        // Retour : la valeur de l'attribut passé en paramètre
        // Paramètre :
        //          - $attribut : attribut dont on veut la valeur
        
        $attr = "attr_$attribut";
        
        if (property_exists($this, $attr)) {
            return $this->$attr;
        } else {
            debug(get_class($this)."->getAttribut() : l'attribut $attr n'existe pas");
            return "";
        }
    }
    
    
    /********* SETTERS *********/
    public function set($valeur) {
        // Rôle : modifier la valeur du champs
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur du champs
        
        $this->valeur = $valeur;
        
        return true;
    }
    
    public function setInBdd($valeur) {
        // Rôle : modifier la valeur de l'attribut "inBdd"
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur de l'attribut
        
        if ($valeur) {
            $this->attr_inBdd = true;
            return true;
        } elseif (!$valeur) {
            $this->attr_inBdd = false;
            return true;
        } else {
            return false;
        }
    }
}
