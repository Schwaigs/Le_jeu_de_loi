<?php
session_start();
require_once '../model/classesPHP/Arbre.php';


echo'<link rel="stylesheet" href="../css/arbreGenealogique.css">';

$arbre = new Arbre();
try{
  //La méthode initArbre créer l'arbre généalogique sous forme de liste en HTML.
  // elle initialise la racine puis lance la création de chaque fraterie de manière récursive.
    $arbre->initArbre();
    if (!isset($_GET['refresh'])){
     /*
     * \var refresh est une variable de session qui permet de rafraichir la page lors de chaque action.
     */
     header('Location: arbreGenealogique.php?refresh=0');
     exit();
   }
}
catch( PDOException $e ) {
    echo 'Erreur : '.$e->getMessage();
    exit;
}
