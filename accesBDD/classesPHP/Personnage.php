<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';
require_once '../accesBDD/classesPHP/Heritage.php';

class Personnage {

    public function __construct(){
    }

    public function choixReligion() : string {   
        //choisi aléatoirement une religion pour la creation d'un personnage 
        //parmis celles disponible dans la bdd selon différentes probabilitées
        $religionAlea;

        $numAlea = rand(1,100);
        /* 75 % de catholiques */
        if ($numAlea < 76){
            $religionAlea = 'catholique';
        }
        /* 20 % de protestant */
        if (($numAlea > 75) && ($numAlea < 96)){
            $religionAlea = 'protestant';
        }
        /* 5 % les autres */
        if ($numAlea > 95){
            $numAlea = rand(1,6);
            switch ($numAlea) {
                case 1 : $religionAlea = 'musulman';
                case 2 : $religionAlea = 'juif';
                case 3 : $religionAlea = 'athee';
                case 4 : $religionAlea = 'hindou';
                case 5 : $religionAlea = 'bouddhiste';
                case 6 : $religionAlea = 'evangelique';
            }
        }

        return $religionAlea;
    }

    public function choixNationnalite() : string { 
        //choisi aléatoirement une nationnalite pour la creation d'un personnage 
        //parmis celles disponible dans la bdd selon différentes probabilitées  
        $nationnaliteAlea;

        $numAlea = rand(1,100);
        /* 95 % de français */
        if ($numAlea < 96){
            $nationnaliteAlea = 'français';
        }
        /* 5 % les autres */
        if ($numAlea > 95){
            $numAlea = rand(1,13);
            switch ($numAlea) {
                case 1 : $nationnaliteAlea = 'allemand';
                case 2 : $nationnaliteAlea = 'autrichien';
                case 3 : $nationnaliteAlea = 'belge';
                case 4 : $nationnaliteAlea = 'britannique';
                case 5 : $nationnaliteAlea = 'danois';
                case 6 : $nationnaliteAlea = 'espagnol';
                case 7 : $nationnaliteAlea = 'hollandais';
                case 8 : $nationnaliteAlea = 'islandais';
                case 9 : $nationnaliteAlea = 'italien';
                case 10 : $nationnaliteAlea = 'luxembourgeois';
                case 11 : $nationnaliteAlea = 'norvegien';
                case 12 : $nationnaliteAlea = 'suedois';
                case 13 : $nationnaliteAlea = 'suisse';
            }
        }
        return $nationnaliteAlea;
    }

    public function chercherOrdreNaissance(int $parent, int $age) : int {
        //cherche dans la base tout les frères et soeurs plus âgés qu'un personnage 
        //pour connaitre son ordre de naissance
        $result = MyPDO::pdo()->prepare("SELECT id FROM personnage WHERE parent = :idParent AND age > :agePerso");
        $idSucces = $result->bindValue(':idParent',$parent, PDO::PARAM_STR);
        $ageSucces = $result->bindValue(':agePerso',$age, PDO::PARAM_STR);
        $result->execute();
        //le nombre de lignes renvoyées par la requete correspond directement au nb de frères et soeurs plus agés
        $nbfrereEtSoeurs = $result->rowCount();
        return $nbfrereEtSoeurs+1;
    }

    public function choixSexe() : string {
        //choisi aléatoirement le sexe pour la creation d'un personnage 
        $numAlea = rand(1,2);
        if($numAlea == 1){
            return 'homme';
        }
        return'femme';
    }

    public function choixEtatSante() : string {
        //choisi aléatoirement un etat de sante pour la creation d'un personnage 
        //parmis ceux disponibles dans la bdd selon différentes probabilitées  
        $etatSanteAlea;

        $numAlea = rand(1,100);
        /* 75 % de bonne sante */
        if ($numAlea < 76){
            $etatSanteAlea = 'bon';
        }
        /* 20 % moyen */
        if (($numAlea > 75) && ($numAlea < 96)){
            $etatSanteAlea = 'moyen';
        }
        /* 5 % faible */
        if ($numAlea > 95){
            $etatSanteAlea = 'faible';
        }
        return $etatSanteAlea;
    }

    public function choixParent() : int {
        //choisi aléatoirement un parent pour la creation d'un personnage 
        //parmis les perso la bdd
        //on récupère les id de chaque parent potentiel
        $resultParents = MyPDO::pdo()->prepare("SELECT id FROM personnage WHERE age < 65 AND age > 15 AND classe!='mort'");
        $resultParents->execute();
        $nbLigne = $resultParents->rowCount();
        //Si pas de parent possible
        if ($nbLigne == 0){
            return 0;
        }

        //on stock tout les id récupérés et on les mets dans un tableau
        $tabIdParents;
        foreach ($resultParents as $row){
            $tabIdParents[] = $row['id'];
        }
    
        //Puis on tire un nombre aléatoire qui correspond à l'indice de l'id d'un parent
        $numAlea = rand(0,$nbLigne-1);
        $parentAlea = $tabIdParents[$numAlea];
    
        return $parentAlea;
    }

    public function creerPersonnage() : int {
        /*On met null pour l'id car la base gère l'auto-incrémentation 
         age toujours 0 vu qu'il s'agit de naissances
         la classe est nulle car on la remplie juste apres l'insertion avec la methode ajoutCouleurPerso()*/
        $result = MyPDO::pdo()->prepare("INSERT INTO personnage VALUES(null,:religion,:nationnalite,:ordreNaissance,0,:sexe,:etatSante,:parent,null)");
    
        $religion = $this->choixReligion();
        echo'religion = '.$religion.'<br>';
        $religionSucces = $result->bindValue(':religion',$religion, PDO::PARAM_STR);
        
        $nationnalite = $this->choixNationnalite();
        echo'nationnalite = '.$nationnalite.'<br>';
        $nationnaliteSucces = $result->bindValue(':nationnalite',$nationnalite, PDO::PARAM_STR);
        
        $sexe = $this->choixSexe();
        echo'sexe = '.$sexe.'<br>';
        $sexeSucces = $result->bindValue(':sexe',$sexe, PDO::PARAM_STR);
        
        $etatSante = $this->choixEtatSante();
        echo'etatSante = '.$etatSante.'<br>';
        $etatSanteSucces = $result->bindValue(':etatSante',$etatSante, PDO::PARAM_STR);
        
        $parent = $this->choixParent();
        //s'il n'y a pas de parent possible on n'execute pas la requete et on renvoie que 0 lignes ont été modifiées dans la bdd
        if($parent == 0){
            //---------------------------rajouter une variable message d'erreur dans la session------------------------------
            return 0;
        }
        echo'parent = '.$parent.'<br>';
        $parentSucces = $result->bindValue(':parent',$parent, PDO::PARAM_STR);
    
        $ordreNaissance = $this->chercherOrdreNaissance($parent,0);
        echo'ordreNaissance = '.$ordreNaissance.'<br>';
        $ordreNaissanceSucces = $result->bindValue(':ordreNaissance',$ordreNaissance, PDO::PARAM_STR);
        
        $result->execute();
        $nbLigne = $result->rowCount();

        $this->ajoutCouleurPerso();
        //on renvoie le nb de lignes modifiées dans la base
        return $nbLigne;
    }

    public function ajoutCouleurPerso() : void {
        //on cherche l'id le plus grand dans la table, c'est celui du dernier personnage ajouté
        $resultID = MyPDO::pdo()->prepare("SELECT max(id) FROM personnage");
        $resultID->execute();
        $id;
        $nbLigne = $resultID->rowCount();
        foreach($resultID as $row){
            $id[] = $row['max(id)'];
        }

        $heritage = new Heritage();
        try{
            //cherche les heritiers possibles
            $heritiers = $heritage->chercherHeritier();
            if($heritiers == null){
            }
            else{
                //on cherche si l'id du nouveau perso est dans la liste des heritiers
                $herit = false;
                foreach ($heritiers as $perso){
                    if ($perso == $id){
                        $herit = true;
                    }
                }

                if ($herit){
                    $heritage->classePersoHeritier($id);
                }
                else{
                    $heritage->classePersoNonHeritier($heritiers);
                }
            }
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    }
}