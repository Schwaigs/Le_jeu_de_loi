<?php
require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

/*
* \class Heritage
* \par Permet de gèrer la recherche des héritiers.
 */
class Heritage {

    public function __construct(){
    }

    /**
    *\fn public function cherchePlusAgeJeune(array $personnages, int $loiOrdreNaissance) : int
    * \brief Cherche le personnage le plus agé ou le plus jeune d'une fraterie selon la loi sur l'odre de naissance qui est mise en place.
    * \pre personnages contient les frères et soeurs d'une même fraterie.
    * \pre loiOrdreNaissance permet d'identifier la loi sur l'ordre de naissance. 0 pour l'ultimogéniture et 1 pour la primogéniture.
    * \return Renvoie l'identifiant du personnage qui correspond à la loi pour cette fraterie.
    */
    public function cherchePlusAgeJeune(array $personnages, int $loiOrdreNaissance) : int {
        $in_valuesAge = implode(',',$personnages);
        /*echo ' cherchePlusAgeJeune() les heritiers du meme parents <br>';
        echo $in_valuesAge.'<br>';*/
        $resultAge = MyPDO::pdo()->prepare("SELECT id,ordreNaissance FROM perso WHERE id in (".$in_valuesAge.")");
        $resultAge->execute();

        $tabAgeEnfant;
        $tabAge;
        foreach ($resultAge as $row){
            $tabAgeEnfant[$row['ordreNaissance']] = $row['id'];
            $tabAge[] = $row['ordreNaissance'];
        }
        //si on cherche le plus jeune
        if($loiOrdreNaissance == 0){
            return $tabAgeEnfant[max($tabAge)];
        }
        return $tabAgeEnfant[min($tabAge)];
    }

    /**
    *\fn public function choisiOrdreNaissanceHeritiers (array $heritiers, int $loiOrdreNaissance) : array
    * \brief Cherche les personnages des différentes frateries qui correspondent selon la loi sur l'odre de naissance qui est mise en place.
    * \pre heritiers contient la liste de tout les héritiers possibles dans la famille.
    * \pre loiOrdreNaissance permet d'identifier la loi sur l'ordre de naissance. 0 pour l'ultimogéniture et 1 pour la primogéniture.
    * \return Renvoie un tableau avec pour clé l'identifiant des personnages qui correspondent à la loi et en valeur l'id de leur parent.
    */
    public function choisiOrdreNaissanceHeritiers (array $heritiers, int $loiOrdreNaissance) : array {
        $enfantHeritier;
        $newListHeritiers;

        /*On creer un tableau qui contitent le nb d'occurence d'un parent chez les heritiers */
        $nbParent;
        foreach ($heritiers as $enfant => $parent){
            //echo ' choisiOdreNaissanceHeritier() parentEnfant :  enfant = '.$enfant.'  =>  parent = '.$parent.' <br>';
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
            //echo ' choisiOdreNaissanceHeritier() nbParent :  parent = '.$parentNB.'  =>  occ = '.$nbOcc.' <br>';
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
                    $corresEnfantParent[$enfant] = $parentNB;
                }
            }
            /*echo 'choisiOdreNaissanceHeritier() les heritiers du meme parents <br>';
            print_r($enfantsMemeParent);
            echo'<br>';
            echo 'choisiOdreNaissanceHeritier() loi ordre naissance '.$loiOrdreNaissance.' <br>';
            echo 'choisiOdreNaissanceHeritier() correspondance enfant parent <br>';
            print_r($corresEnfantParent);
            echo'<br>';*/

            /* pour chaque parent qui a plusieurs enfants on cherche celui qui correspond à la loi */
            $enfantHeritier = $this->cherchePlusAgeJeune($enfantsMemeParent,$loiOrdreNaissance);
            /*echo 'choisiOdreNaissanceHeritier() apres plusJeuneAge <br>';
            print_r($enfantHeritier);
            echo'<br>';*/
            //on vide le tableau $enfantsMemeParent pour la prochaine fratrie
            $i = 0;
            while(!empty($enfantsMemeParent)){
                unset($enfantsMemeParent[$i]);
                $i++;
            }
            $parent = $corresEnfantParent[$enfantHeritier];
            //echo 'choisiOdreNaissanceHeritier() parent = '.$parent.'  => enfantHeritier = '.$enfantHeritier.'<br>';
            //et on l'ajoute à la liste des heritiers
            $newListHeritiers[$enfantHeritier] =  $parent;
        }
        /*echo 'choisiOdreNaissanceHeritier() les heritiers et leurs parents <br>';
        print_r($newListHeritiers);
        echo'<br>';*/
        return $newListHeritiers;
    }


    /**
    *\fn public function chercherHeritiersLois() 
    * \brief Cherche les personnages qui sont héritiers en comparant leurs caractéristiques aux lois mises en place.
    * \return Renvoie un tableau avec pour clé l'identifiant du personnages et en valeur l'identifiant de son parent.
    */
    public function chercherHeritiersLois() {
        /*On stocke dans un tableau en cle l'id de l'héritier et en valeur son parent */
        $parentEnfant = [];

        /* valeurs sur lequels ont peut avoir des lois */
        $ordreNaissanceVal;
        $religionVal;
        $sexeVal;
        $richesseVal;

        /* On cherche les lois misent en places */
        $resultLoi = MyPDO::pdo()->prepare("SELECT parametre, paramVal FROM lois WHERE misEnPlace = 1 and parametre != 'sante'");
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
        $resultPerso = MyPDO::pdo()->prepare("SELECT id,parent,religion,sexe,richesse FROM perso WHERE classe not in ('mort','roi')");
        $resultPerso->execute();
        $nbLigne = $resultPerso->rowCount();

        //s'il n'y a aucun heritier le joueur à perdu
        if($nbLigne == 0){
            $_SESSION['messageFin'] = "Vous n'avez plus aucun héritier, ainsi, une autre lignée a récupéré le trône. Vous avez perdu.";
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
            $_SESSION['messageFin'] = "Vous n'avez plus aucun héritier, ainsi, une autre lignée a récupéré le trône. Vous avez perdu.";
            return null;
        }
        /* si il n'y a pas de lois concernant l'odre de naissance on a les héritiers tels quels */
        if (!(isset($ordreNaissanceVal))){
            return $parentEnfant;
        }

        /* On souhaite maintenant voir dans notre liste d'heritiers s'il a des frères et soeurs,
         il faut alors prendre l'ainée ou le plus jeune selon la loi */
        $heritiersOrdre = $this->choisiOrdreNaissanceHeritiers($parentEnfant, $ordreNaissanceVal);
        return $heritiersOrdre;
    }

    /**
    *\fn public function chercherHeritiers()
    * \brief Cherche les héritiers ayant le lien de parenté le plus proche avec le roi.
    *\return Renvoie un tableau avec pour valeurs les identifiants des héritiers.
    */
    public function chercherHeritiers() {
        //Fonction lancée après un changement dans les loi pour mettre un jour l'arbre en fonction des classes des persos et mettre a jour les jauges
        /*On compte le nombre d'héritier possible */
        $parentEnfant = $this->chercherHeritiersLois();

        //s'il n'y a aucun heritier
        if(empty($parentEnfant)){
            return null;
        }

        $ResHeritiers = [];
        //si on a des heritiers potentiels
        /*On creer un tableau avec uniquement les id des heritiers en valeur*/
        $heritiers = [];
        foreach ($parentEnfant as $enfant => $parent){
            $heritiers[] = $enfant;
        }

        $nbHeritiers = count($heritiers);
        /*si on a un seul id dans le tableau des héritiers alors c'est lui le roi*/
        if ($nbHeritiers == 1) {
            $ResHeritiers = $heritiers;
        }
        else{
            /*si plusieurs héritiers le choix se fait par proximite avec le roi*/
            //On récupère des infos sur le roi actuel
            $resultAncienRoi = MyPDO::pdo()->prepare("SELECT id,parent FROM perso WHERE classe='roi'");
            $resultAncienRoi->execute();
            $idRoiActuel;
            $idParentRoiActuel;
            foreach ($resultAncienRoi as $row){
                $idRoiActuel = $row['id'];
                $idParentRoiActuel = $row['parent'];
            }
            //echo 'id roi actuel = '.$idRoiActuel.' <br>';
            /*On regarde si parmis les héritiers on a :
            - des frères et soeurs du roi : proximité maximale
            - des enfants du roi : priorite intermediare
            - des heritiers dans aucun des deux cas : non prioritaires */
            $heritiersFS = []; //Freres soeurs
            $heritiersE = []; //Enfants
            $heritiersA = []; //Autres
            foreach ($parentEnfant as $enfant => $parent){
                //echo ' parentEnfant :  enfant = '.$enfant.'  =>  parent = '.$parent.' <br>';
                if ($parent == $idParentRoiActuel){
                    //echo '  Freres soeurs :  enfant = '.$enfant.'<br>';
                    $heritiersFS[] = $enfant;
                }
                elseif ($parent == $idRoiActuel){
                    //echo '  Enfants :  enfant = '.$enfant.'<br>';
                    $heritiersE[] = $enfant;
                }
                else{
                    //echo '  Autres :  enfant = '.$enfant.'<br>';
                    $heritiersA[] = $enfant;
                }
            }

            if (!empty($heritiersFS)){
                /*echo 'freres soeurs <br>';
                print_r($heritiersFS);
                echo '<br>';*/
                $ResHeritiers = $heritiersFS;
                /*echo ' resheritiers freres soeurs <br>';
                print_r($ResHeritiers);
                echo '<br>';*/
            }
            elseif (!empty($heritiersE)){
                /*echo 'enfants <br>';
                print_r($heritiersE);
                echo '<br>';*/
                $ResHeritiers = $heritiersE;
                /*echo ' resheritiers enfants <br>';
                print_r($ResHeritiers);
                echo '<br>';*/
            }
            else{
                /*echo 'autres <br>';
                print_r($heritiersE);
                echo '<br>';*/
                $ResHeritiers = $heritiersA;
                /*echo ' resheritiers autres <br>';
                print_r($ResHeritiers);
                echo '<br>';*/
            }
        }
        //echo 'resHeritiers <br>';
        print_r($ResHeritiers);

        //met a jour la basse de donnée pour l'affichage en couleur de l'arbre
        $this->classePersoHeritier($ResHeritiers);
        $this->classePersoNonHeritier($ResHeritiers);
        return $ResHeritiers;
    }

    /**
    *\fn public function classePersoHeritier (array $heritiers) : void
    * \brief Met à jour dans la base la classe des personnages qui sont des héritiers.
    * \pre heritiers contient la liste de tout les héritiers possibles dans la famille.
    */
    public function classePersoHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages pouvant être des heritiers
        $in_heritiers = implode(',',$heritiers);
        $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='heritier' WHERE id in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    /**
    *\fn public function classePersoNonHeritier (array $heritiers) : void
    * \brief Met à jour dans la base la classe des personnages qui ne sont pas des héritiers.
    * \pre heritiers contient la liste de tout les héritiers possibles dans la famille.
    */
    public function classePersoNonHeritier (array $heritiers) : void {
        //change dans la base de donnée l'attribut classe des personnages ne pouvant pas être des heritiers
        $in_heritiers = implode(',',$heritiers);
        $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi') AND id not in (".$in_heritiers.")");
        $resultHerit->execute();
    }

    /**
    *\fn public function choisiRoi () : int
    * \brief Cherche l'héritier le plus légitime et le fait devenir roi.
    * \return Renvoie l'identifant du nouveau roi.
    */
    public function choisiRoi () : int {
        /*On compte le nombre d'héritier possible */
        $heritiers = $this->chercherHeritiers();

        //s'il n'y a aucun heritier le joueur à perdu
        if(empty($heritiers)){
            $_SESSION['jeu'] = 'perdu';
            $_SESSION['messageFin'] = "Vous n'avez pas d'héritier pour vous remplacer sur le trône, ainsi, une autre famille le recupère. Vous avez perdu.";
            header('Location: fin.php');
            exit();
        }

        $nbHeritiers = count($heritiers);
        $idRoi;
        /*si on a un seul id dans le tableau des héritiers alors c'est lui le roi*/
        if ($nbHeritiers == 1) {
            $idRoi = $heritiers[0];
        }
        /*Si plusieurs heritiers on cherche par santé*/
        else{
            $idRoi = $this->meilleurSante($heritiers);
        }

        /* Si le nouveau roi n'est pas français alors le joueur à perdu */
        $paysNewRoi;
        $ageNewRoi;
        $prenomNewRoi;
        $resultNewRoi = MyPDO::pdo()->prepare("SELECT * from perso WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        foreach ($resultNewRoi as $row){
            $paysNewRoi = $row['nationnalite'];
            $ageNewRoi = $row['age'];
            $prenomNewRoi = $row['prenom'];
        }
        if($paysNewRoi != 'France'){
            $_SESSION['jeu'] = 'perdu';
            $_SESSION['messageFin'] = "L'héritier qui est monté sur le trône à votre mort était marié a un étranger, ainsi, un autre royaume a récupéré vos terres. Vous avez perdu.";
            header('Location: fin.php');
            exit();
        }

        /*On met a jour la bdd */
        $resultMess = MyPDO::pdo()->prepare("SELECT prenom from perso WHERE classe='roi'");
        $idSucces = $resultMess->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultMess->execute();
        $prenomRoi;
        foreach ($resultMess as $row){
            $prenomRoi = $row['prenom'];
        }
        /* l'ancien roi est destitué et meurt */
        $resultAncienRoi = MyPDO::pdo()->prepare("UPDATE perso SET classe='mort' WHERE classe='roi'");
        $resultAncienRoi->execute();
        $nbLigne = $resultAncienRoi->rowCount();

        /* On change le nouveau roi */
        $resultNewRoi = MyPDO::pdo()->prepare("UPDATE perso SET classe='roi' WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        $nbLigne = $resultNewRoi->rowCount();

        $_SESSION['message'] = "Le roi ".$prenomRoi." est mort. Son héritier ".$prenomNewRoi." monte alors sur le trône.";
        //On met à jour les jauges de relation avec les 3 ordres
        $this->majJauges($idRoi);

        //Cherche les nouveaux héritiers pour les couleurs sur l'arbre
        $this->majHeritiersSansJauges();

        return $idRoi;
    }

    
    /**
    *\fn public function majHeritiers() : void
    * \brief Met à jour les héritiers et les relations suite à un tour de jeu.
    */
    public function majHeritiers() : void {
        /*On compte le nombre d'héritier possible */
        $heritiers = $this->chercherHeritiers();

        //s'il n'y a aucun heritier
        if(empty($heritiers)){
            //tous les personnages passent en non heritier
            $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi')");
            $resultHerit->execute();
        }
        else{
            $nbHeritiers = count($heritiers);
            $idRoi;

            /*si on a un seul id dans le tableau des héritiers*/
            if ($nbHeritiers == 1) {
                //alors on met à jour les jauges en fonction de lui
                $this->majJauges($heritiers[0]);
            }
            /*Si plusieurs heritiers on cherche par santé*/
            else{
                $heritSante = $this->meilleurSanteListe($heritiers);
                //on regarde si on a plusieurs personnages qui sont favorisés par la santé
                $nbHeritiers = count($heritSante);
                if($nbHeritiers == 1){
                    //on met à jour les jauges en fonction de l'unique heritier
                    $this->majJauges($heritSante[0]);
                }
                else{
                    $_SESSION['noblesse'] -= 10;
                    $_SESSION['clerge'] -= 10;
                    $_SESSION['tiersEtat'] -= 10;
                }
            }
        }
    }

    public function majHeritiersSansJauges() : void {
        /*On compte le nombre d'héritier possible */
        $heritiers = $this->chercherHeritiers();

        //s'il n'y a aucun heritier
        if(empty($heritiers)){
            //tous les personnages passent en non heritier
            $resultHerit = MyPDO::pdo()->prepare("UPDATE perso SET classe='nonHeritier' WHERE classe not in ('mort','roi')");
            $resultHerit->execute();
        }
        else{
            $nbHeritiers = count($heritiers);

            /*Si plusieurs heritiers on cherche par santé*/
            if ($nbHeritiers > 1) {
                $heritSante = $this->meilleurSanteListe($heritiers);
            }
        }
    }
    
    /**
    *\fn public function majJauges(int $idRoi) : void
    * \brief Met à jour les jauges de relations avec les différents ordres en fonction de l'héritier le plus légitime ou du nouveau roi.
    * \pre idRoi l'identfiant de l'héritier le plus légitime ou le nouveau roi
    */
    public function majJauges(int $idRoi) : void {
        $richesseNewRoi;
        $religionNewRoi;
        $affiniteNewRoi;
        //On récupère les caractéristiques du nouveau roi ou du prochain héritier
        $resultNewRoi = MyPDO::pdo()->prepare("SELECT * from perso WHERE id = :idRoi");
        $idSucces = $resultNewRoi->bindValue(':idRoi',$idRoi, PDO::PARAM_INT);
        $resultNewRoi->execute();
        $nouveauScoreNoblesse= $_SESSION['noblesse'];
        $nouveauScoreClerge= $_SESSION['clerge'];
        $nouveauScoreTE= $_SESSION['tiersEtat'];
        foreach ($resultNewRoi as $row){
            $richesseNewRoi = $row['richesse'];
            $religionNewRoi = $row['religion'];
            $affiniteNewRoi = $row['affinite'];
        }
        //Si le personnage est riche les relations avec la noblesse s'améliorent mais celles avec le tiers-état se détériorent
        if($richesseNewRoi == 1){
            $nouveauScoreNoblesse +=10;
            $nouveauScoreTE -=10;
        }
        //Et inversement s'il est plutôt pauvre
        else{
            $nouveauScoreNoblesse -=10;
            $nouveauScoreTE +=10;
        }
        //Si le personnage est catholique les relations avec le clergé s'améliorent
        if($religionNewRoi == 'catholique'){
            $nouveauScoreClerge +=10;
        }
        //sinon elles se détériorent
        else{
            $nouveauScoreClerge -=10;
        }
        //Enfin chaque personnage possède une affinité avec un des 3 ordre ce qui améliore les relations avec ce dernier
        if($affiniteNewRoi == 'noblesse'){
            $nouveauScoreNoblesse +=10;
        }
        elseif($affiniteNewRoi == 'tiers état'){
            $nouveauScoreTE +=10;
        }
        else{
            $nouveauScoreClerge +=10;
        }
        //On remplace les jauges par les nouvelles valeurs et on verifie qu'on ne dépassse pas 100 qui est le max et 0 qui est le min
        if($nouveauScoreClerge > 100){
            $nouveauScoreClerge = 100;
        }
        else if($nouveauScoreClerge < 0){
            $nouveauScoreClerge = 0;
        }
        if($nouveauScoreNoblesse > 100){
            $nouveauScoreNoblesse = 100;
        }
        else if($nouveauScoreNoblesse < 0){
            $nouveauScoreNoblesse = 0;
        }
        if($nouveauScoreTE > 100){
            $nouveauScoreTE = 100;
        }
        else if($nouveauScoreTE < 0){
            $nouveauScoreTE = 0;
        }
        $_SESSION['noblesse'] = $nouveauScoreNoblesse;
        $_SESSION['clerge'] = $nouveauScoreClerge;
        $_SESSION['tiersEtat'] = $nouveauScoreTE;
    }

    /**
    *\fn public function meilleurSante(array $heritiers) : int
    * \brief Cherche l'héritier qui a le meilleur état de santé
    * \pre heritiers contient la liste des héritiers possibles.
    */
    public function meilleurSante(array $heritiers) : int {
        //Si pas de loi priorisant la santé alors on tire au sort l'héritier parmis ceux possibles
        $resultLoi = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace = 1 and parametre = 'sante'");
        $resultLoi->execute();
        $nbLigne = $resultLoi->rowCount();
        if ($nbLigne == 0) {
            $indexAlea = rand( 0, count($heritiers)-1);
            return $heritiers[$indexAlea];
        }

        //Si la loi priorisant la santé est mise en place alors on tire au sort selon les héritiers qui vont le mieux
        //On cherche l'heritier qui se trouve dans le meilleur état de sante
        $in_heritiers = implode(',',$heritiers);
        $resultSante = MyPDO::pdo()->prepare("SELECT id,etatSante from perso WHERE id in (".$in_heritiers.")");
        $resultSante->execute();

        //On classe les héitiers en groupes selon les 3 états de santé possibles
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

    /**
    *\fn public function meilleurSanteListe(array $heritiers) : array
    * \brief Cherche les heritiers qui ont le meilleur état de santé
    * \pre heritiers contient la liste des héritiers possibles.
    */
    public function meilleurSanteListe(array $heritiers) : array {
        //Si pas de loi priorisant la santé alors on garde les mêmes heritiers
        $resultLoi = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace = 1 and parametre = 'sante'");
        $resultLoi->execute();
        $nbLigne = $resultLoi->rowCount();
        if ($nbLigne == 0) {
            return $heritiers;
        }

        //Si la loi priorisant la santé est mise en place alors on prends les héritiers qui vont le mieux
        //On cherche les heritiers qui se trouvent dans le meilleur état de sante
        $in_heritiers = implode(',',$heritiers);
        $resultSante = MyPDO::pdo()->prepare("SELECT id,etatSante from perso WHERE id in (".$in_heritiers.")");
        $resultSante->execute();

        //On classe les héitiers en groupes selon les 3 états de santé possibles
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

        $ResHeritiers = [];
        //priorite à la meilleur sante
        if (!empty($bon)){
            $ResHeritiers = $bon;
        }
        elseif (!empty($moyen)){
            $ResHeritiers = $moyen;
        }
        else{
            $ResHeritiers =$mavais;
        }
        //met a jour la basse de donnée pour l'affichage en couleur de l'arbre
        $this->classePersoHeritier($ResHeritiers);
        $this->classePersoNonHeritier($ResHeritiers);
        return $ResHeritiers;
    }
}