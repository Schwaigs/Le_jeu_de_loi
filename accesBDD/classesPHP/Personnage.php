<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';
require_once '../accesBDD/classesPHP/Heritage.php';
/*
* \class Personnage
* \brief Permet de gèrer les personnages.
 */
class Personnage {

    public function __construct(){
    }

    /**
    *\fn public function choixReligion() : string
    * \brief Choisit aléatoirement une religion pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie la religion sous forme de chaine de caractères.
    */
    public function choixReligion() : string {
        //choisi aléatoirement une religion pour la creation d'un personnage
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
            $religionAlea = 'autre';
        }

        return $religionAlea;
    }

    /**
    *\fn public function choixPrenomFemme() : string
    * \brief Choisit aléatoirement un prénom féminin pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie le prénom sous forme de chaine de caractères.
    */
    public function choixPrenomFemme() : string {
        //choisi aléatoirement un prenom pour la creation d'un personnage
        $prenomAlea;
        $tabPrenom=['Adélaïde','Adeline','Anastasie','Astrid','Aude','Aurore','Athénaïs','Arégonde','Anne','Agnès','Bertille','Blanche','Béatrice','Bérangère',
                    'Clothilde','Cécile','Constance','Cunégonde','Cyrielle','Claudine','Désirée','Edith','Elaine','Edwige','Elisabeth','Flore','Frénégonde',
                    'Guenièvre','Gwendoline','Galadrielle','Hildegarde','Henriette','Isabelle','Isaure','Jeanne','Jaqueline','Ludivine','Louise','Marie',
                    'Mélissandre','Morgane','Mathilde','Mélusine','Marguerite','Ondine','Pétronille','Regine','Rolande','Raymonde','Viviane','Yseult'];
        $numAlea = rand(0,50);
        $prenomAlea = $tabPrenom[$numAlea];
        return $prenomAlea;
    }

    /**
    *\fn public function choixPrenomHomme() : string
    * \brief Choisit aléatoirement un prénom masculin pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie le prénom sous forme de chaine de caractères.
    */
	  public function choixPrenomHomme() : string {
        //choisi aléatoirement un prenom pour la creation d'un personnage
        $prenomAlea;
        $tabPrenom=['Armand','Auguste','Amaury','Albert','Ambroise','Arnaud','Arthur','Barthélemy','Bertrand','Balthazar','Charles','Clotaire','Clovis','Côme',
                    'Cédric','Conrad','Claudes','Dagobert','Eloi','Enguerrand','Eudes','Fernand','Flavien','Florimond','François','Florent','Gaulthier','Gaspard',
                    'Gérald','Godefroy','Grégoire','Gilles','Hugues','Henri','Jaques','Jean','Lancelot','Louis','Norbert','Odin','Perceval','Pierrick','Pierre',
                    'Philippe','Robin','Robert','Ruffin','Richard','Roland','Raymond','Tanguy','Thibault','Théobald','Tristan','Wilfrid','Ysangrin','Yves'];
        $numAlea = rand(0,56);
        $prenomAlea = $tabPrenom[$numAlea];
        return $prenomAlea;
    }

    /**
    *\fn public function choixNationnalite() : string
    * \brief Choisit aléatoirement une nationnalité pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie la nationnalité sous forme de chaine de caractères.
    */
    public function choixNationnalite() : string {
        //choisi aléatoirement une nationnalite pour la creation d'un personnage
        $nationnaliteAlea;

        $numAlea = rand(1,100);
        /* 95 % de français */
        if ($numAlea < 96){
            $nationnaliteAlea = 'France';
        }
        /* 5 % les autres */
        if ($numAlea > 95){
            $nationnaliteAlea = 'Étranger';
        }
        return $nationnaliteAlea;
    }

    /**
    *\fn public function chercherOrdreNaissance(int $parent) : int
    * \brief Cherche combientième de sa fraterie est le nouveau personnage.
    * \pre parent contient l'identifiant du parent du nouveau personnage.
    * \return Renvoie sa position dans la fraterie
    */
    public function chercherOrdreNaissance(int $parent) : int {
        //cherche dans la base tout les frères et soeurs plus âgés qu'un personnage
        //pour connaitre son ordre de naissance
        $result = MyPDO::pdo()->prepare("SELECT id FROM persoDe". $_SESSION['login'] ." WHERE parent = :idParent");
        $idSucces = $result->bindValue(':idParent',$parent, PDO::PARAM_INT);
        $result->execute();
        //le nombre de lignes renvoyées par la requete correspond directement au nb de frères et soeurs plus agés
        $nbfrereEtSoeurs = $result->rowCount();
        return $nbfrereEtSoeurs+1;
    }

    /**
    *\fn public function choixSexe() : string
    * \brief Choisit aléatoirement un sexe pour la creation d'un personnage.
    * \return Renvoie le sexe sous forme de chaine de caractères.
    */
    public function choixSexe() : string {
        //choisi aléatoirement le sexe pour la creation d'un personnage
        $numAlea = rand(1,2);
        if($numAlea == 1){
            return 'homme';
        }
        return'femme';
    }

    /**
    *\fn public function choixRichesse() : int
    * \brief Choisit aléatoirement un niveau de richesse faible ou élevé pour la creation d'un personnage.
    * \return Renvoie la richesse sous forme d'entier.
    */
    public function choixRichesse() : int {
        //choisi aléatoirement la richesse pour la creation d'un personnage
        //1 = élevé et 0 = faible
        $numAlea = rand(0,1);
        return $numAlea;
    }

    /**
    *\fn public function choixEtatSante() : string
    * \brief Choisit aléatoirement un état de santé pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie l'état de santé sous forme de chaine de caractères.
    */
    public function choixEtatSante() : string {
        //choisi aléatoirement un etat de sante pour la creation d'un personnage
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

    /**
    *\fn public function choixAffinite() : string
    * \brief Choisit aléatoirement une affinité avec l'un des 3 ordres pour la creation d'un personnage selon différentes probabilitées.
    * \return Renvoie l'affinité sous forme de chaine de caractères.
    */
    public function choixAffinite() : string {
        //choisi aléatoirement une affinité pour la creation d'un personnage
        $affiniteAlea;

        $numAlea = rand(1,100);
        /* 40 % de noblesse */
        if ($numAlea < 41){
            $affiniteAlea = 'noblesse';
        }
        /* 40 % clergé */
        if (($numAlea > 40) && ($numAlea < 81)){
            $affiniteAlea = 'clergé';
        }
        /* 20 % tiers état */
        if ($numAlea > 80){
            $affiniteAlea = 'tiers état';
        }
        return $affiniteAlea;
    }

    /**
    *\fn public function choixParent() : int
    * \brief Choisit aléatoirement un parent pour la creation d'un personnage.
    * \return Renvoie l'identifiant du parent.
    */
    public function choixParent() : int {
        //choisi aléatoirement un parent pour la creation d'un personnage
        //parmis les perso la bdd
        //on récupère les id de chaque parent potentiel
       $resultParents = MyPDO::pdo()->prepare("SELECT id FROM persoDe". $_SESSION['login'] ." WHERE age < 50 AND age > 9 AND classe!='mort'");

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

    /**
    *\fn public function creerPersonnage() : int
    * \brief Creer un nouveau personnage dans la famille à l'aide de caractéristiques aléatoires.
    * \return Renvoie le nombre de lignes modifiées dans la base.
    */
    public function creerPersonnage() : int {
        /*On met null pour l'id car la base gère l'auto-incrémentation
         age toujours 0 vu qu'il s'agit de naissances
         la classe est nonHeritier car à la naissance il est trop jeune*/
        $result = MyPDO::pdo()->prepare("INSERT INTO persoDe". $_SESSION['login'] ." VALUES(null,:prenom,:religion,:nationnalite,:ordreNaissance,0,:sexe,:etatSante,:parent,'heritier',:richesse,:affinite)");

        //chaque caractéristique est choisit aléatoirement à l'aide des différentes fonctions
        $religion = $this->choixReligion();
        //echo'religion = '.$religion.'<br>';
        $religionSucces = $result->bindValue(':religion',$religion, PDO::PARAM_STR);

        $nationnalite = $this->choixNationnalite();
        //echo'nationnalite = '.$nationnalite.'<br>';
        $nationnaliteSucces = $result->bindValue(':nationnalite',$nationnalite, PDO::PARAM_STR);

        $sexe = $this->choixSexe();
        //echo'sexe = '.$sexe.'<br>';
        $sexeSucces = $result->bindValue(':sexe',$sexe, PDO::PARAM_STR);

        $richesse = $this->choixRichesse();
        //echo'richesse = '.$richesse.'<br>';
        $richesseSucces = $result->bindValue(':richesse',$richesse, PDO::PARAM_INT);

        //Le choix du prénom se fait en fonction du sexe
        $prenom;
        if($sexe == 'homme'){
            $prenom = $this->choixPrenomHomme();
        }
        else{
            $prenom = $this->choixPrenomFemme();
        }
        $prenomSucces = $result->bindValue(':prenom',$prenom, PDO::PARAM_STR);
        //echo'prenom = '.$prenom.'<br>';

        $etatSante = $this->choixEtatSante();
        //echo'etatSante = '.$etatSante.'<br>';
        $etatSanteSucces = $result->bindValue(':etatSante',$etatSante, PDO::PARAM_STR);

        $affinite = $this->choixAffinite();
        //echo'affinite = '.$affinite.'<br>';
        $affiniteSucces = $result->bindValue(':affinite',$affinite, PDO::PARAM_STR);

        $parent = $this->choixParent();
        //s'il n'y a pas de parent possible on n'execute pas la requete et on renvoie que 0 lignes ont été modifiées dans la bdd
        if($parent == 0){
            $_SESSION['message'] = "Aucun membre de votre famille ne peut avoir d'enfant actuellement";
            return 0;
        }
        //echo'parent = '.$parent.'<br>';
        $parentSucces = $result->bindValue(':parent',$parent, PDO::PARAM_INT);

        $ordreNaissance = $this->chercherOrdreNaissance($parent);
        //echo'ordreNaissance = '.$ordreNaissance.'<br>';
        $ordreNaissanceSucces = $result->bindValue(':ordreNaissance',$ordreNaissance, PDO::PARAM_INT);

        $result->execute();
        $nbLigne = $result->rowCount();

        //on renvoie le nb de lignes modifiées dans la base
        return $nbLigne;
    }

    /**
    *\fn public function vieillirPerso() : void
    * \brief Les personnages encore en vie viellissent après chaque tour de jeu.
    */
    public function vieillirPerso() : void {
        //Chaque tour de jeu représente un période de 3 ans
        $result = MyPDO::pdo()->prepare("UPDATE persoDe". $_SESSION['login'] ." SET age = age+5 where classe <> 'mort'");
        $result->execute();
    }

    /**
    *\fn public function mortPerso() : int
    * \brief Fait mourir des personnages de la famille de manière aléatoire.
    * \return Renvoie le nombre de morts.
    */
    public function mortPerso() : int {
        //Les personnages autres que le roi meurent de manière aléatoire
        $result = MyPDO::pdo()->prepare("SELECT id,age,etatSante From persoDe". $_SESSION['login'] ." Where classe not in ('mort','roi')");
        $result->execute();
        $listePerso = [];
        $probaMort;

        //En fonction de son état de santé et de son âge chaque personnage se voit attribué une certaine probabilité de mourir
        foreach ($result as $row){
            if ($row['age'] <5){
                $probaMort = 30;
            }
            else if ($row['age'] >= 5  && $row['age'] < 30){
                $probaMort = 12;
            }
            else if ($row['age'] >= 30  && $row['age'] < 60){
                $probaMort = 35;
            }
            else{
                $probaMort = 90;
            }

            if ($row['etatSante'] == 'bon'){
                $probaMort *= 1;
            }
            else if ($row['etatSante'] == 'moyen'){
                $probaMort *= 1.2;
            }
            else{
                $probaMort *= 1.5;
            }

            $listePerso[$row['id']] = $probaMort;
        }
        $nbPerso = count($listePerso);
        //echo'nbPerso base = '.$nbPerso.'<br>';

        $compteurMort =0;

        //Puis on parcours la liste de nos personnages et pour chacun on tire un nombre aléatoire
        foreach ($listePerso as $idPerso => $proba){
            $numAlea = rand(1,100);
            //echo'Perso '.$idPerso.' proba = '.$proba.' numAlea = '.$numAlea.'<br>';
            //Si le chiffre tiré est inférieur à sa probabilité de mourir alors il meurt
            if($proba > $numAlea){
                $resultMort= MyPDO::pdo()->prepare("UPDATE persoDe". $_SESSION['login'] ." SET classe='mort' WHERE id=:id");
                $idSucces = $resultMort->bindValue(':id',$idPerso, PDO::PARAM_INT);
                $resultMort->execute();
                $compteurMort++;
            }
        }
        //On renvoie le nombre de personnages morts
        return $compteurMort;
    }
}
