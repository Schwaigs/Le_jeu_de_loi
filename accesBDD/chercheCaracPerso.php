<?php

require_once 'MyPDO.php';

function caracPerso($id) : array {

    $caracteristiques = [];

    $result = MyPDO::pdo()->prepare("SELECT * FROM personnage WHERE id = :id");
    $idSucces = $result->bindValue(':id',$id, PDO::PARAM_INT);
    $result->execute();
    $nbLigne = $result->rowCount();
    if ($nbLigne !=1){
        return $caracteristiques;
    }
    
    //stockage des données récupérées
    foreach($result as $row){
            $caracteristiques['parent']= $row['parent'];
            $caracteristiques['sexe']= $row['sexe'];
            $caracteristiques['age']= $row['age'];
            $caracteristiques['ordreNaissance']= $row['ordreNaissance'];
            $caracteristiques['religion']= $row['religion'];
            $caracteristiques['nationnalite']= $row['nationnalite'];
            $caracteristiques['etatSante']= $row['etatSante'];
            if ($row['classe']=='mort'){
                $caracteristiques['estEnVie']='non';
            }
            else{
                $caracteristiques['estEnVie']='oui';
            }
    }
    return $caracteristiques;
}

