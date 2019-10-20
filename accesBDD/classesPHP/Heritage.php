<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

class Heritage {

    public function __construct(){
    }

    public function cherchePlusAgeJeune(array $personnages, int $loiOrdreNaissance) : int {
        $in_valuesAge = implode(',',$personnages);
        $resultAge = MyPDO::pdo()->prepare("SELECT id,age FROM personnage WHERE id in (".$in_valuesAge.")");
        $resultAge->execute();
    
        $tabAgeEnfant;
        $tabAge;
        foreach ($resultAge as $row){
            $tabAgeEnfant[$row['age']] = $row['id'];
            $tabAge[] = $row['age'];
        }
        //si on cherche le plus jeune
        if($loiOrdreNaissance == -1){
            return $tabAgeEnfant[min($tabAge)];
        }
        return $tabAgeEnfant[max($tabAge)];
    }
    
    public function choisiOrdreNaissanceHeritiers (array $heritiers, int $loiOrdreNaissance) : array {
        $enfantHeritier;
        $newListHeritiers;
    
        /*On creer un tableau qui contitent le nb d'occurence d'un parent chez les heritiers */
        $nbParent;
        foreach ($heritiers as $enfant => $parent){
            if ( (!(isset($nbParent))) || (!(array_key_exists($parent,$nbParent))) ){
                $nbParent[$parent] = 1;
            }
            else{
                $nbParent[$parent] += 1;
            }
        }

        $enfantsMemeParent;
        /* On regarde l'occurence de chaque parent */
        foreach ($nbParent as $parentNB => $nbOcc){
            //s'il n'y a qu'une occurence l'enfant est héritier et on passe au parent suivant
            if($nbOcc == 1){
                foreach ($heritiers as $enfant => $parentH){
                    if ($parentH == $parentNB){ 
                        $newListHeritiers[] = $enfant;
                    }
                }
                continue;
            }
            /* si plusieurs enfants, on fait une liste des enfants de ce parent*/
            foreach ($heritiers as $enfant => $parentH){
                if ($parentH == $parentNB){
                    $enfantsMemeParent[] = $enfant;         
                }
            }
            /* pour chaque parent qui a plusieurs enfants on cherche celui qui correspond à la loi */
            $enfantHeritier = $this->cherchePlusAgeJeune($enfantsMemeParent,$loiOrdreNaissance);

            //on vide le tableau $enfantMemeParent pour la prochaine fratrie
            $i = 0;
            while(!empty($enfantsMemeParent)){
                unset($enfantsMemeParent[$i]);
                $i++;
            }

            //et on l'ajoute à la liste des heritiers
            $newListHeritiers[] = $enfantHeritier;
        }
        
        return $newListHeritiers;
    }

    public function chercherHeritier(){
        /* tableau contenant les identifiant des héritiers */
        $heritiers;
        /*On stocke dans un tableau en cle l'enfant et en valeur le parent */
        $parentEnfant;
    
        /* valeurs sur lequels ont peut avoir des lois */
        $etatSanteVal;
        $ordreNaissanceVal;
        $religionVal;
        $sexeVal;
    
        /* On cherche les lois misent en places */
        $resultLoi = MyPDO::pdo()->prepare("SELECT parametre, paramVal FROM lois WHERE misEnPlace = 1");
        $resultLoi->execute();
        $nbLigne = $resultLoi->rowCount();
        
        /* lecture des lois existantes */
        foreach ($resultLoi as $row){
            switch ($row['parametre']){
                case 'etatSante' : $etatSanteVal = $row['paramVal']; break;
                case 'ordreNaissance' : $ordreNaissanceVal = $row['paramVal']; break;
                case 'religion' : $religionVal = $row['paramVal']; break;
                case 'sexeVal' : $sexeVal = $row['paramVal']; break;
            }
        }
       
        /* On cherche les personnages de notre base qui sont encore en vie et français*/
        $resultPerso = MyPDO::pdo()->prepare("SELECT id,parent,etatSante,religion,sexe FROM personnage WHERE classe not in ('mort','roi')");
        $resultPerso->execute();
        $nbLigne = $resultPerso->rowCount();

        //s'il n'y a aucun heritier le joueur à perdu
        if($nbLigne == 0){
            // --------------------------- mettre en place une variable dans le tableau $_SESSION------------------------
            return null;
        }
    
    
        /*on parcours la collection de nos personnage*/
        foreach ($resultPerso as $row){
            if((isset($etatSanteVal)) && ($etatSanteVal != $row['etatSante'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            if((isset($religionVal)) && ($religionVal != $row['religion'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            if((isset($sexeVal)) && ($sexeVal != $row['sexe'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            /* On ajoute l'héritier à la liste */
            $heritiers[] = $row['id'];
            /*On stocke dans un tableau en cle l'enfant et en valeur le parent */
            $parentEnfant[$row['id']] = $row['parent'];
        }
    
        /* si il n'y a pas de lois concernant l'odre de naissance on a les héritiers tels quels */
        if (!(isset($ordreNaissanceVal))){
            return $heritiers;
        }

        //si aucun des personnages de la base ne correspond au lois on a pas d'heritier le joueur à perdu
        if(!isset($heritiers)){
            return null;
        }
        /* On souhaite maintenant voir dans notre liste d'heritiers s'il a des frères et soeurs,
         il faut alors prendre l'ainée ou le plus jeune selon la loi */
        $heritiersOrdre = $this->choisiOrdreNaissanceHeritiers($parentEnfant, $ordreNaissanceVal);
        return $heritiersOrdre;
    }


    public function classePersoHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages pouvant être des heritiers
        $in_heritiers = implode(',',$heritiers);

        $resultAffichage = MyPDO::pdo()->prepare("SELECT id from personnage WHERE id in (".$in_heritiers.")");
        $resultAffichage->execute();

        $resultHerit = MyPDO::pdo()->prepare("UPDATE personnage SET classe='heritier' WHERE id in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    public function classePersoNonHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages ne pouvant pas être des heritiers
        $in_heritiers = implode(',',$heritiers);
        
        $resultAffichage = MyPDO::pdo()->prepare("SELECT id from personnage WHERE classe not in ('mort','roi') AND id not in (".$in_heritiers.")");
        $resultAffichage->execute();
        
        $resultHerit = MyPDO::pdo()->prepare("UPDATE personnage SET classe='nonHeritier' WHERE classe not in ('mort','roi') AND id not in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    public function choisiRoi () : int {
        /* On compte le nombre d'héritier possible */
        $heritiers = $this->chercherHeritier();
        //s'il n'y a aucun heritier le joueur à perdu
        if($heritiers == null){
            // --------------------------- mettre en place une variable dans le tableau $_SESSION------------------------
            return 0;
        }

        //met a jour la basse de donnée pour l'affichage en couleur de l'arbre
        $this->classePersoHeritier($heritiers);
        $this->classePersoNonHeritier($heritiers);

        
        $nbHeritiers = count($heritiers);
        $idRoi;
        /*si on a un seul id dans le tableau des héritiers alors c'est lui le roi*/
        if ($nbHeritiers == 1) {
            $idRoi = $heritiers[0];
        }
    
        /*si plusieurs héritiers le choix se fait aléatoirement*/
        if ($nbHeritiers > 1){
            /*on tire un nombre aléatoire qui representera l'index de l'héritier choisit*/
            $indexAlea = rand( 0, $nbHeritiers-1);
            $idRoi = $heritiers[$indexAlea];
        }
    
        /*On met a jour la bdd */
        /* l'ancien roi est destitué et meurt */
        $resultAncienRoi = MyPDO::pdo()->prepare("UPDATE personnage SET classe='mort' WHERE classe='roi'");
        $resultAncienRoi->execute();
        $nbLigne = $resultAncienRoi->rowCount();

        /* On change le nouveau roi */
        $resultNewRoi = MyPDO::pdo()->prepare("UPDATE personnage SET classe='roi' WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        $nbLigne = $resultNewRoi->rowCount();

        return $idRoi;
    }
}