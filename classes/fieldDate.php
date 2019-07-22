<?php

// Classe décrivant un champs de type "DATE", hérite de la classe field

class fieldDate extends field {
    
    
    /********* ATTRIBUTS *********/
    protected $attr_type = "DATE";
    
    
    /********* GETTERS *********/
    public function get() {
        // Rôle : retourne le contenu de l'attribut valeur
        // Retour : le contenu de l'attribut valeur (date du jour si l'attribut est vide) (sous format SQL)
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->valeur;
        } else {
            
            $dateFormat = new DateTime();
            
            return $dateFormat;
        }
    }
    
    public function getValue() {
        // Rôle : retourne la valeur du champs
        // Retour : la valeur du champs sous format SQL
        // Paramètre : aucun
        
        if (isset($this->valeur)) {
            return $this->valeur->format("Y-m-d H:i:s");
        } else {
            
            $dateFormat = new DateTime();
            
            return $dateFormat->format("Y-m-d H:i:s");
        }
    }
    
    public function html() {
        // Rôle : retourne la valeur du champs sous un format adapté pour l'affichage
        // Retour : la valeur du champs sous un format adapté pour l'affichage
        // Paramètre : aucun
        
        $date = $this->get()->format("d-m-Y");
        $heure = $this->get()->format("H:i:s");
        
        $affichage = nl2br(htmlentities("le $date à $heure"));
        
        return $affichage;
    }
    
    
    /********* SETTERS *********/
    public function set($valeur) {
        // Rôle : modifier la valeur du champs
        // Retour : true / false
        // Paramètre :
        //          - $valeur : nouvelle valeur (date sous format mySql, Y-m-d H:i:s)
        
        // Création de l'objet date
        if (is_string($valeur)) {
            if (!$date = DateTime::createFromFormat("Y-m-d H:i:s", $valeur)) {
                debug(get_class($this)."->set() : le paramètre n'a pas été rentré dans le bon format");
                return false;
            }
        } else {
            $date = $valeur;
        }
        
        if (get_class($date) !== "DateTime") {
            debug(get_class($this)."->set() : l'objet passé en paramètre n'est pas de la bonne classe");
            return false;
        }
        
        return parent::set($date);
    }
}
