<?php

// Ouverture de la session
session_start();

// Affichage des erreurs
ini_set("display_errors",1);
error_reporting(E_ALL);

// Include des fonctions
include_once "lib/functions.php";

// Include des classes model
include_once "classes/model.php";
include_once "classes/field.php";
include_once "classes/session.php";

// Initialisation de la session
session::init();