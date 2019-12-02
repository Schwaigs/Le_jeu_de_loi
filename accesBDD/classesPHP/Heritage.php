<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

class Heritage {

    public function __construct(){
    }

    public function cherchePlusAgeJeune(array $personnages, int $loiOrdreNaissance) : int {
        $in_valuesAge = implode(',',$personnages);
        $resultAge = MyPDO::pdo()->prepare("SELECT id,age FROM perso WHERE id in (".$in_valuesAge.")");
        $resultAge->execute();

        $tabAgeEnfant;
        $tabAge;
        foreach ($resultAge as $row){
            $tabAgeEnfant[$row['age']] = $row['id'];
            $tabAge[] = $row['age'];
        }
        //si on cherche le plus jeune
        if($loiOrdreNaissance == 0){
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
        /*echo 'choisiOdreNaissanceHeritier() les occurence parents <br>';
        print_r($nbParent);
        echo'<br>';*/
        $enfantsMemeParent;
        $corresEnfantParent;
        /* On regarde l'occurence de chaque parent */
        foreach ($nbParent as $parentNB => $nbOcc){
            //s'il n'y a qu'une occurence l'enfant est héritier et on passe au parent suivant
            if($nbOcc == 1){
                foreach ($heritiers as $enfant => $parentH){
                    if ($parentH == $parentNB){
                        $newListHeritiers[$enfant] = $parentH;
                    }
                }
                continue;
            }
            /* si plusieurs enfants, on fait une liste des enfants de ce parent*/
            foreach ($heritiers as $enfant => $parentH){
                if ($parentH == $parentNB){
                    $enfantsMemeParent[] = $enfant;
                    $corresEnfantParent[$enfant] = $parent;
                }
            }
            /*echo 'choisiOdreNaissanceHeritier() les heritiers du meme parents <br>';
            print_r($enfantsMemeParent);
            echo'<br>';
            echo 'choisiOdreNaissanceHeritier() loi ordre naissance '.$loiOrdreNaissance.' <br>';*/

            /* pour chaque parent qui a plusieurs enfants on cherche celui qui correspond à la loi */
            $enfantHeritier = $this->cherchePlusAgeJeune($enfantsMemeParent,$loiOrdreNaissance);

            //on vide le tableau $enfantsMemeParent pour la prochaine fratrie
            $i = 0;
            while(!empty($enfantsMemeParent)){
                unset($enfantsMemeParent[$i]);
                $i++;
            }
            $parent = $corresEnfantParent[$enfantHeritier];
            //et on l'ajoute à la liste des heritiers
            $newListHeritiers[$enfantHeritier] =  $parent;
        }
        /*echo 'choisiOdreNaissanceHeritier() les heritiers et leurs parents <br>';
        print_r($newListHeritiers);
        echo'<br>';*/
        return $newListHeritiers;
    }

    public function chercherHeritier(){
        /*On stocke dans un tableau en cle l'id de l'héritier et en valeur son parent */
        $parentEnfant = [];

        /* valeurs sur lequels ont peut avoir des lois */
        $ordreNaissanceVal;
        $religionVal;
        $sexeVal;
        $richesseVal;

        /* On cherche les lois misent en places */
        $resultLoi = MyPDO::pdo()->prepare("SELECT parametre, paramVal FROM lois WHERE misEnPlace = 1");
        $resultLoi->execute();
        $nbLigne = $resultLoi->rowCount();

        /* lecture des lois existantes */
        foreach ($resultLoi as $row){
            switch ($row['parametre']){
                case 'ordreNaissance' : $ordreNaissanceVal = $row['paramVal']; break;
                case 'religion' : $religionVal = $row['paramVal']; break;
                case 'sexe' : $sexeVal = $row['paramVal']; break;
                case 'richesse' : $richesseVal = $row['paramVal']; break;
            }
        }

        /* On cherche les personnages de notre base qui sont encore en vie*/
        $resultPerso = MyPDO::pdo()->prepare("SELECT id,parent,religion,sexe,richesse FROM perso WHERE classe not in ('mort','roi') and age>10");
        $resultPerso->execute();
        $nbLigne = $resultPerso->rowCount();

        //s'il n'y a aucun heritier le joueur à perdu
        if($nbLigne == 0){
            return null;
        }


        /*on parcours la collection de nos personnage*/
        foreach ($resultPerso as $row){
            if((isset($religionVal)) && ($religionVal != $row['religion'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            if((isset($sexeVal)) && ($sexeVal != $row['sexe'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            if((isset($richesseVal)) && ($richesseVal != $row['richesse'])){
                continue; /* le perso ne peut pas etre un heritier on passe au suivant */
            }
            /* On ajoute l'héritier à la liste */
            $parentEnfant[$row['id']] = $row['parent'];
        }
        //si aucun des personnages de la base ne correspond aux lois on a pas d'heritier le joueur à perdu
        if(empty($parentEnfant)){
            return null;
        }
        /* si il n'y a pas de lois concernant l'odre de naissance on a les héritiers tels quels */
        if (!(isset($ordreNaissanceVal))){
            return $parentEnfant;
        }
        /*echo 'chercheheritier() les heritiers et leurs parents <br>';
        print_r($parentEnfant);
        echo'<br>';*/

        /* On souhaite maintenant voir dans notre liste d'heritiers s'il a des frères et soeurs,
         il faut alors prendre l'ainée ou le plus jeune selon la loi */
        $heritiersOrdre = $this->choisiOrdreNaissanceHeritiers($parentEnfant, $ordreNaissanceVal);
        return $heritiersOrdre;
    }


    public function classePersoHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages pouvant être des heritiers
        $in_heritiers = implode(',',$heritiers);
        $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='heritier' WHERE id in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    public function classePersoNonHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages ne pouvant pas être des heritiers
        $in_heritiers = implode(',',$heritiers);
        $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi') AND id not in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    public function choisiRoi () : int {
        /*On compte le nombre d'héritier possible */
        $parentEnfant = $this->chercherHeritier();

        //s'il n'y a aucun heritier le joueur à perdu
        if(empty($parentEnfant)){
            $_SESSION['jeu'] = 'perdu';
            return 0;
        }

        /*On creer un tableau avec uniquement les id des heritiers en valeur*/
        $heritiers = [];
        foreach ($parentEnfant as $enfant => $parent){
            $heritiers[] = $enfant;
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

        $resultAncienRoi = MyPDO::pdo()->prepare("SELECT id,parent FROM perso WHERE classe='roi'");
        $resultAncienRoi->execute();
        $idRoiActuel;
        $idParentRoiActuel;
        foreach ($resultAncienRoi as $row){
            $idRoiActuel = $row['id'];
            $idParentRoiActuel = $row['parent'];
        }

        /*si plusieurs héritiers le choix se fait par proximite avec le roi puis par priorité de sante et enfin aléatoirement*/
        if ($nbHeritiers > 1){
            /*On regarde si parmis les héritiers on a :
            - des frères et soeurs du roi : proximité maximale
            - des enfants du roi : priorite intermediare
            - des heritiers dans aucun des deux cas : non prioritaires */
            $heritiersFS = []; //Freres soeurs
            $heritiersE = []; //Enfants
            $heritiersA = []; //Autres
            foreach ($parentEnfant as $enfant => $parent){
                if ($parent == $idParentRoiActuel){
                    $heritiersFS[] = $enfant;
                }
                elseif ($parent == $idRoiActuel){
                    $heritiersE[] = $enfant;
                }
                else{
                    $heritiersA[] = $enfant;
                }
            }

            if (!empty($heritiersFS)){
                //si qu'un seul heritier frère et soeurs alors c'est lui le prochain roi
                if(count($heritiersFS) == 1){
                    $idRoi = $heritiersFS[0];
                }
                //sinon par état de santé
                $idRoi = $this->meilleurSante($heritiersFS);
            }
            elseif (!empty($heritiersE)){
                //si un seul heritier enfant du roi alors c'est lui le prochain roi
                if(count($heritiersE) == 1){
                    $idRoi = $heritiersE[0];
                }
                //sinon par état de santé
                $idRoi = $this->meilleurSante($heritiersE);
            }
            else {
                //si un seul heritier non proioritaire alors c'est lui le prochain roi
                if(count($heritiersA) == 1){
                    $idRoi = $heritiersA[0];
                }

                //sinon par état de santé
                $idRoi = $this->meilleurSante($heritiersA);
            }
        }

        /* Si le nouveau roi n'est pas français alors le joueur à perdu */
        $paysNewRoi;
        $resultNewRoi = MyPDO::pdo()->prepare("SELECT * from perso WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        foreach ($resultNewRoi as $row){
            $paysNewRoi = $row['nationnalite'];
        }
        if($paysNewRoi != 'France'){
            $_SESSION['jeu'] = 'perdu';
            return null; //---------------------------------voir si return null ou 0-------------------
        }

        /*On met a jour la bdd */
        /* l'ancien roi est destitué et meurt */
        $resultAncienRoi = MyPDO::pdo()->prepare("UPDATE perso SET classe='mort' WHERE classe='roi'");
        $resultAncienRoi->execute();
        $nbLigne = $resultAncienRoi->rowCount();

        /* On change le nouveau roi */
        $resultNewRoi = MyPDO::pdo()->prepare("UPDATE perso SET classe='roi' WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        $nbLigne = $resultNewRoi->rowCount();

        if($_SESSION['peutEnfant'] ==0){ //si l'ancien roi ne pouvais plus avoir d'enfant le nouveau si
            $_SESSION['peutEnfant'] ==1;
        }

        //On met à jour les jauges
        $this->majJauges($idRoi);

        return $idRoi;
    }

    public function majArbreHeritiers() : void {
        //Fonction lancer après un nouvel événement pour mettre un jour l'arbre en fonction des classes des persos
        /*On compte le nombre d'héritier possible */
        $parentEnfant = $this->chercherHeritier();
        //si on a des heritiers potentiels
        if (!empty($parentEnfant)){
            /*echo 'les heritiers et leurs parents <br>';
            print_r($parentEnfant);
            echo'<br>';*/
            /*On creer un tableau avec uniquement les id des heritiers en valeur*/
            $heritiers = [];
            foreach ($parentEnfant as $enfant => $parent){
                $heritiers[] = $enfant;
            }
            /*echo 'les heritiers <br>';
            print_r($heritiers);
            echo'<br>';*/

            //met a jour la basse de donnée pour l'affichage en couleur de l'arbre
            $this->classePersoHeritier($heritiers);
            $this->classePersoNonHeritier($heritiers);
        }
        else{
            //sinon tout le monde passe en non heritier
            $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi')");
            $resultHerit->execute();
        }
        
    }

    public function majHeritiersLois() : void {
        //Fonction lancer après un changement dans les loi pour mettre un jour l'arbre en fonction des classes des persos et mettre a jour les jauges
        /*On compte le nombre d'héritier possible */
        $parentEnfant = $this->chercherHeritier();
        //si on a des heritiers potentiels
        if (!empty($parentEnfant)){
            /*On creer un tableau avec uniquement les id des heritiers en valeur*/
            $heritiers = [];
            foreach ($parentEnfant as $enfant => $parent){
                $heritiers[] = $enfant;
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

            $resultAncienRoi = MyPDO::pdo()->prepare("SELECT id,parent FROM perso WHERE classe='roi'");
            $resultAncienRoi->execute();
            $idRoiActuel;
            $idParentRoiActuel;
            foreach ($resultAncienRoi as $row){
                $idRoiActuel = $row['id'];
                $idParentRoiActuel = $row['parent'];
            }
           /* echo 'id Roi actuel = '.$idRoiActuel.'<br>';
            echo 'id parent Roi actuel = '.$idParentRoiActuel.'<br>';*/

            /*si plusieurs héritiers le choix se fait par proximite avec le roi*/
            if ($nbHeritiers > 1){
                /*On regarde si parmis les héritiers on a :
                - des frères et soeurs du roi : proximité maximale
                - des enfants du roi : priorite intermediare
                - des heritiers dans aucun des deux cas : non prioritaires */
                $heritiersFS = []; //Freres soeurs
                $heritiersE = []; //Enfants
                $heritiersA = []; //Autres
                foreach ($parentEnfant as $enfant => $parent){
                    if ($parent == $idParentRoiActuel){
                        $heritiersFS[] = $enfant;
                    }
                    elseif ($parent == $idRoiActuel){
                        $heritiersE[] = $enfant;
                    }
                    else{
                        $heritiersA[] = $enfant;
                    }
                }

                if (!empty($heritiersFS)){
                    //si qu'un seul heritier frère et soeurs alors c'est lui le prochain roi
                    if(count($heritiersFS) == 1){
                        $idRoi = $heritiersFS[0];
                    }
                }
                elseif (!empty($heritiersE)){
                    //si un seul heritier enfant du roi alors c'est lui le prochain roi
                    if(count($heritiersE) == 1){
                        $idRoi = $heritiersE[0];
                    }
                }
                else {
                    //si un seul heritier non proioritaire alors c'est lui le prochain roi
                    if(count($heritiersA) == 1){
                        $idRoi = $heritiersA[0];
                    }
                }
            }
            //Si on a un unique heritier alors on met à jour les jauges
            if (isset($idRoi)){
                $this->majJauges($idRoi);
            }
        }
        //sinon tout le monde passe en non heritier
        else{
            $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi')");
            $resultHerit->execute();
        }
    }

    public function majJauges(int $idRoi) : void {
        $richesseNewRoi;
        $religionNewRoi;
        $affiniteNewRoi;
        $resultNewRoi = MyPDO::pdo()->prepare("SELECT * from perso WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        foreach ($resultNewRoi as $row){
            $richesseNewRoi = $row['richesse'];
            $religionNewRoi = $row['religion'];
            $affiniteNewRoi = $row['affinite'];
        }
        if($richesseNewRoi == 1){
            $_SESSION['noblesse'] +=10;
            $_SESSION['tiersEtat'] -=10;
        }
        else{
            $_SESSION['noblesse'] -=10;
            $_SESSION['tiersEtat'] +=10;
        }
        if($religionNewRoi == 'catholique'){
            $_SESSION['clerge'] +=10;
        }
        else{
            $_SESSION['clerge'] -=10;
        }
        if($affiniteNewRoi == 'noblesse'){
            $_SESSION['noblesse'] +=10;
        }
        elseif($affiniteNewRoi == 'tiers état'){
            $_SESSION['tiersEtat'] +=10;
        }
        else{
            $_SESSION['clerge'] +=10;
        }
    }

    public function meilleurSante(array $heritiers) : int {
        //On cherche l'heritier qui se trouve dans le meilleur état de sante//
        $in_heritiers = implode(',',$heritiers);
        $resultSante = MyPDO::pdo()->prepare("SELECT id,etatSante from perso WHERE id in (".$in_heritiers.")");
        $resultSante->execute();

        $bon = [];
        $moyen= [];
        $mauvais = [];

        foreach ($resultSante as $row){
            if ($row['etatSante']=='bon'){
                $bon[] =  $row['id'];
            }
            elseif ($row['etatSante']=='moyen'){
                $moyen[] = $row['id'];
            }
            else{
                $mauvais[] = $row['id'];
            }
        }

        //priorite à la meilleur sante

        if (!empty($bon)){
            //si qu'un seul heritier en bonne sante alors c'est lui le prochain roi
            if(count($bon) == 1){
                return $bon[0];
            }
            //sinon aleéatoire
            $indexAlea = rand( 0, count($bon)-1);
            return $bon[$indexAlea];
        }

        elseif (!empty($moyen)){
            //si un seul heritier en moyenne sante alors c'est lui le prochain roi
            if(count($moyen) == 1){
                return $moyen[0];
            }
            //sinon aleéatoire
            $indexAlea = rand( 0, count($moyen)-1);
            return $moyen[$indexAlea];
        }

        //si un seul heritier en mauvaise sante alors c'est lui le prochain roi
        if(count($mauvais) == 1){
            return $mauvais[0];
        }
        //sinon aleéatoire
        $indexAlea = rand( 0, count($mauvais)-1);
        return $mavais[$indexAlea];

    }
}
