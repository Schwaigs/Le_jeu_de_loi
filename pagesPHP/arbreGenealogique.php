<?php
require_once '../accesBDD/classesPHP/Arbre.php';


echo'<link rel="stylesheet" href="../css/arbreGenealogique.css">';

        $arbre = new Arbre();
        try{
            $arbre->initArbre();
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
