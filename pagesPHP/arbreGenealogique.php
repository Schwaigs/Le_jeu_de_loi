<?php
require_once '../accesBDD/classesPHP/Arbre.php';

        $arbre = new Arbre();
        try{
            $arbre->initArbre();
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
