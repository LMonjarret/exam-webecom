<?php

// Classe d'un objet métier, décrit une fiche de jardinage. Hérite de "model"

class model_fiche extends model{
    
    
    /********* ATTRIBUTS *********/
    protected $table = "fiche";
    protected $primaryKey = "id";
    
    
    /********* INITIALISATION *********/
    protected function initFields() {
        // Rôle : initialiser les différents champs de l'objet courant (surchargé par la classe fille)
        // Retour : aucun
        // Paramètre : aucun
        
        $this->makeFieldInt("id");
        $this->makeFieldLink("auteur", "nom", "user");
        $this->makeFieldStr("titre");
        $this->makeFieldStr("texte");
        $this->makeFieldStr("image");
        $this->makeFieldDate("dt_creation");
        $this->makeFieldDate("dt_lastModif");
    }
    
    
    /********* CONSTRUCT *********/
    public function __construct($data = "") {
        // Rôle : constructeur de l'objet
        // Retour : aucun
        // Paramètre : aucun
        
        parent::__construct($data);
        
        $this->makeFieldInt("note");
        $this->initNote();
    }
    
    
    /********* RELATIONS BDD *********/
    public function dernieresFiches() {
        // Rôle : retourner les cinq dernières fiches créées dans la base de données
        // Retour : un tableau d'objet model_fiche
        // Paramètre : aucun
        
        // Construction de la requête
        $sql = "SELECT * FROM `fiche` ORDER BY `dt_creation` DESC LIMIT 5;";
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute()) {
            debug(get_class($this)."->dernieresFiches() : échec de la requête $sql");
            return [];
        }
        
        // Récupération du résultat
        $liste = [];
        $classe = get_class($this);
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne);
        }
        
        return $liste;
    }
    
    public function listeTriee($tri) {
        // Rôle : retourner une liste d'objet fiche en fonction d'un critère de recherche
        // Retour : une liste d'objet
        // Paramètre :
        //          - $tri : une liste contenant chaque mot clé servant à la recherche
        
        // Construction de la requête
        $sql = "SELECT `fiche`.`id`, `fiche`.`titre`, `fiche`.`texte`, `fiche`.`auteur` FROM `fiche` LEFT JOIN `user` ON `fiche`.`auteur` = `user`.`id` WHERE ";
        $start = true;
        $param = [];
        $compteur = 1;
        
        foreach ($tri as $keyword) {
            $mot = "mot_$compteur";
            
            if ($start) {
                $sql .= "`fiche`.`titre` LIKE :$mot";
                $sql .= " OR `fiche`.`texte` LIKE :$mot";
                $sql .= " OR `user`.`nom` LIKE :$mot";
                $sql .= " OR `user`.`prenom` LIKE :$mot";
                $start = false;
            } else {
                $sql .= " OR `fiche`.`titre` LIKE :$mot";
                $sql .= " OR `fiche`.`texte` LIKE :$mot";
                $sql .= " OR `user`.`nom` LIKE :$mot";
                $sql .= " OR `user`.`prenom` LIKE :$mot";
            }
            
            $param[":$mot"] = "%$keyword%";
            $compteur++;
        }
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        
        if (!$req->execute($param)) {
            debug(get_class($this)."->listeTriee() : échec de la requête $sql");
            return [];
        }
        
        // Récupération du résultat
        $liste = [];
        $classe = get_class($this);
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne);
        }
        
        return $liste;
    }
    
    public function listeFavori() {
        // Rôle : retourner la liste des objets fiche mis en favori par l'utilisateur connecté
        // Retour : une liste d'objet fiche
        // Paramètre : aucun
        
        // Préparation de la requête
        $sql = "SELECT `fiche`.`id` FROM `fiche` LEFT JOIN `favori` ON `favori`.`fiche` = `fiche`.`id` WHERE `favori`.`user` = :compte;";
        $param = [":compte" => session::getUserId()];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->listeFavori() : échec de la requête $sql");
            return [];
        }
        
        // Récupération du résultat
        $liste = [];
        $classe = get_class($this);
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne["id"]);
        }
        
        return $liste;
    }
    
    public function listeOwn() {
        // Rôle : retourner la liste des objets fiche créés par l'utilisateur connecté
        // Retour : une liste d'objet fiche
        // Paramètre : aucun
        
        // Préparation de la requête
        $sql = "SELECT * FROM `fiche` WHERE `auteur` = :user;";
        $param = [":user" => session::getUserId()];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->listeFavori() : échec de la requête $sql");
            return [];
        }
        
        // Récupération du résultat
        $liste = [];
        $classe = get_class($this);
        
        while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
            $liste[] = new $classe($ligne);
        }
        
        return $liste;
    }
    
    protected function initNote() {
        // Rôle : récupère toutes les notes de la fiche et en calcule la moyenne
        // Retour : true / false
        // Paramètre : aucun
        
        // On spécifie que le champs n'est pas dans la base de données
        $this->getChamps("note")->setInBdd(false);
        
        // Construction de la requête
        $sql = "SELECT AVG(`note`.`note`) AS 'moyenne' FROM `note` LEFT JOIN `fiche` on `note`.`fiche` = `fiche`.`id` WHERE `note`.`fiche` = :idFiche;";
        $param = [":idFiche" => $this->getId()];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->initNote() : échec de la requête $sql");
            return false;
        }
        
        // Récupération du résultat
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
        $this->set("note", round($resultat["moyenne"], 1));
        
        return true;
    }
    
    public function verifyNote() {
        // Rôle : vérifie si l'utilisateur courant a noté la fiche
        // Retour : true / false
        // Paramètre : aucun
        
        // Construction de la requête
        $sql = "SELECT `note`.`user` FROM `note` LEFT JOIN `fiche` ON `note`.`fiche` = `fiche`.`id` WHERE `note`.`fiche` = :idFiche AND `note`.`user` = :idUser;";
        $param = [":idFiche" => $this->getId(), ":idUser" => session::getUserId()];
        
        // Préparation de la requête
        $req = self::getBdd()->prepare($sql);
        
        // Exécution de la requête
        if (!$req->execute($param)) {
            debug(get_class($this)."->verifyNote() : échec de la requête $sql");
            return false;
        }
        
        // Récupération du résultat
        if ($req->rowCount() >= 1) {
            // L'utilisateur a voté
            return true;
        } else {
            return false;
        }
    }
    
    
    /********* CONTROLEURS *********/
    public function action_form() {
        // Rôle : contrôleur de l'action form, affichant un formulaire en fonction d'un mode
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération du mode
        $mode = isset($_GET["mode"]) ? $_GET["mode"] : "";
        
        // Affichage du formulaire
        if ($mode !== "insert" and $mode !== "update") {
            include "views/pages/accueil.php";
            exit;
        } elseif ($mode === "update") {
            
            // Vérification des droits de modifications
            if (session::getUser()->getValue("profil") === "ABO") {
                if ($this->getValue("auteur") !== session::getUserId()) {
                    include "views/pages/accueil.php";
                    exit;
                }
            } elseif (session::getUser()->getValue("profil") === "EXP") {
                if ($this->get("auteur")->getValue("profil") === "EXP") {
                    include "views/pages/accueil.php";
                    exit;
                }
            }
            
            include "views/pages/formUpdateFiche.php";
            exit;
        }

        include "views/pages/formFiche.php";
    }
    
    public function action_insert() {
        // Rôle : contrôleur de l'action insert, insérant l'objet courant dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification des paramètres
        foreach ($_POST as $key => $valeur) {
            if ($key !== "img" and empty($valeur)) {
                include "views/pages/formFiche.php";
                exit;
            } else {
                $this->set($key, $valeur);
            }
        }
        
        $mode = "insert";
        
        // Premier insert pour récupérer l'id
        $this->insert();
        
        if ($_FILES["img"]["error"] === 0) {
            
            // Génération des types autorisés
            $typeAutorise = ["image/png", "image/jpg", "image/jpeg"];
            
            // Vérification du type
            if (!in_array($_FILES["img"]["type"], $typeAutorise)) {
                include "views/pages/formFiche.php";
                exit;
            }
            
            // Création du chemin
            $chemin = "images/fiches/user".session::getUserId()."/";
            
            // Création du nom
            $info = new SplFileInfo($_FILES["img"]["name"]);
            $nom = "fiche".$this->getId().".".$info->getExtension();
            
            // Création du dossier
            if (!file_exists($chemin)) {
                mkdir($chemin, 0777, true);
            }
            
            // Déplacement du fichier
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $chemin.$nom)) {
                $this->set("image", $chemin.$nom);
            } else {
                include "views/pages/formFiche.php";
            }
        }
        
        // Assignation de l'id de l'auteur
        $this->set("auteur", session::getUserId());
        
        // Assignation de la date de création et de la date de dernière modification
        $this->set("dt_creation", new DateTime());
        $this->set("dt_lastModif", new DateTime());
        
        // Insertion de l'article dans la base de données
        if ($this->update()) {
            header("Location: index.php?module=fiche&action=detail&id=".$this->getId());
            exit;
        } else {
            include "views/pages/formFiche.php";
        }
    }
    
    public function action_update() {
        // Rôle : contrôleur de l'action update, mettant à jour l'objet courant dans la base de données
        // Retour : aucun
        // Paramètre : aucun
        
        // Vérification des droits de modifications
        if (session::getUser()->getValue("profil") === "ABO") {
            if ($this->getValue("auteur") !== session::getUserId()) {
                include "views/pages/accueil.php";
                exit;
            }
        } elseif (session::getUser()->getValue("profil") === "EXP") {
            if ($this->get("auteur")->getValue("profil") === "EXP") {
                include "views/pages/accueil.php";
                exit;
            }
        }
        
        // Vérification du POST
        $mode = "update";
        
        foreach ($_POST as $key => $valeur) {
            if ($key !== "img" and empty($valeur)) {
                include "views/pages/formUpdateFiche.php";
                exit;
            }
            
            $this->set($key, $valeur);
        }
        
        // Modification de la date de dernière modification
        $this->set("dt_lastModif", new DateTime());
        
        // Update de l'objet
        if ($this->update()) {
            header("Location: index.php?module=fiche&action=detail&id=".$this->getId());
            exit;
        } else {
            include "views/pages/formUpdateFiche.php";
        }
    }
    
    public function action_detail() {
        // Rôle : contrôleur de l'action detail, affichant une fiche au complet
        // Retour : aucun
        // Paramètre : aucun
        
        include "views/pages/fiche.php";
    }
    
    public function action_liste() {
        // Rôle : contrôleur de l'action liste, affichant une liste de toutes les fiches
        // Retour : aucun
        // Paramètre : aucun
        
        $liste = $this->liste();
        
        include "views/pages/listeFiche.php";
    }
    
    public function action_listeTri() {
        // Rôle : contrôleur de l'action listeTri, affichant une liste triée en fonction de mots-clés choisi par l'utilisateur
        // Retour : aucun
        // Paramètre : aucun
        
        // Récupération des mots-clés
        $keywords = isset($_POST["keywords"]) ? $_POST["keywords"] : "";
        
        // Vérification des paramètres
        if (empty($keywords)) {
            header("Location: index.php?module=fiche&action=liste");
            exit;
        }
        
        // Nettoyage des mot-clés
        $keywords = str_replace([",", "?", ";", ".", ":", "/", "!", "§", "%"], "", $keywords);
        
        // Explode de la chaîne de caractère
        $keywords = explode(" ", $keywords);
        
        $liste = $this->listeTriee($keywords);
        
        include "views/pages/listeFiche.php";
    }
    
    public function action_listeFavori() {
        // Rôle : contrôleur de l'action listeFavori, affiche une liste des fiches mise en favoris par l'utilisateur connecté
        // Retour : aucun
        // Paramètre : aucun
        
        $liste = $this->listeFavori();
        
        include "views/pages/listeFiche.php";
    }
    
    public function action_own() {
        // Rôle : contrôleur de l'action own, affiche une liste des fiches créées par l'utilisateur connecté
        // Retour : aucun
        // Paramètre : aucun
        
        $liste = $this->listeOwn();
        
        include "views/pages/listeFiche.php";
    }
}
