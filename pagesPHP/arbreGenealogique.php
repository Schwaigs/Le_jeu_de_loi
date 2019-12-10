<?php
require_once '../accesBDD/classesPHP/Arbre.php';


echo'<link rel="stylesheet" href="../css/arbreGenealogique.css">';

$arbre = new Arbre();
try{
  //La méthode initArbre créer l'arbre généalogique sous forme de liste en HTML.
  // elle initialise la racine puis lance la création de chaque fraterie de manière récursive.
    $arbre->initArbre();
}
catch( PDOException $e ) {
    echo 'Erreur : '.$e->getMessage();
    exit;
}
