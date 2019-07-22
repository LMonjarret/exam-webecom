<?php

// Classe décrivant un champs de type "STRING", hérite de la classe field

class fieldStr extends field {
    
    
    /********* ATTRIBUTS *********/
    protected $attr_type = "STR";
    
    
    /********* GETTERS *********/
    public function get() {
        // Rôle : retourne le contenu de l'attribut valeur
        // Retour : le contenu de l'attribut valeur
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->valeur;
        } else {
            return "";
        }
    }
    
    
    /********* SETTERS *********/
    public function set($valeur) {
        // Rôle : modifier la valeur du champs
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur du champs
        
        // Vérification du paramètre
        $value = strval($valeur);
        
        if (!is_string($value)) {
            debug(get_class($this)."->set() : le paramètre passé n'est pas une chaîne de caractère");
            return false;
        }
        
        return parent::set($value);
    }
}
