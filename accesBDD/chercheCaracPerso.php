<?php

require_once 'MyPDO.php';

function caracPerso($id) : array {

    $caracteristiques = [];

    $result = MyPDO::pdo()->prepare("SELECT * FROM perso WHERE id = :id");
    $idSucces = $result->bindValue(':id',$id, PDO::PARAM_INT);
    $result->execute();
    $nbLigne = $result->rowCount();
    if ($nbLigne !=1){
        return $caracteristiques;
    }
    $idParent;
    //stockage des données récupérées
    foreach($result as $row){
            $idParent = $row['parent'];
            $caracteristiques['prenom']= $row['prenom'];
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
    if ($idParent == null){
        $caracteristiques['parent'] = 'Roland';
    }
    else{
        $result = MyPDO::pdo()->prepare("SELECT prenom FROM perso WHERE id = :parent");
        $idSucces = $result->bindValue(':parent',$idParent, PDO::PARAM_INT);
        $result->execute();
        foreach($result as $row){
            $caracteristiques['parent']= $row['prenom'];
        }
    }
    return $caracteristiques;
}
