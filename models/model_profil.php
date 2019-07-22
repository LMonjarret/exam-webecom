<?php

// Classe d'un objet métier, décrit un profil utilisateur. Hérite de "model"

class model_profil extends model {
    
    
    /********* ATTRIBUTS *********/
    protected $table = "profil";
    protected $primaryKey = "code";
    
    
    /********* INITIALISATION *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldInt("code");
        $this->makeFieldStr("libelle");
    }
}
