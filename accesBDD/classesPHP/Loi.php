<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

require_once '../accesBDD/classesPHP/Heritage.php';
/*
* \class Loi
* \brief Permet de gèrer une loi précisement.
 */
class Loi {

    /*
    * \param parametre contient la catégorie sur laquelle porte la loi.
    */
    private $_parametre;
    /*
    * \param  paramVal contient la caractéristique qui est favorisé dans la catégorie
    */
    private $_paramVal;

    /**
    *\fn public function __construct(string $parametre,$paramVal)
    * \brief Constucteur d'une loi.
    * \pre parametre contient la catégorie sur laquelle porte la loi.
    * \pre paramVal contient la caractéristique qui est favorisé dans la catégorie.
    */
    public function __construct(string $parametre,$paramVal){
        $this->_parametre = $parametre;
        $this->_paramVal= $paramVal;
    }

    /**
    *\fn public function ajoutLoi() : int
    * \brief Met à jour dans la bdd la loi qui a été votée ainsi que toutes celles de la même catégorie
    * \return Renvoie le nombre de lignes modifiées. 0 en cas d'erreur.
    */
    public function ajoutLoi() : int {
        //On test si le joueur a le droit de mettre en place une loi
        $droit = $this->verifRelation();
        if ($droit){

            ///On met mis en place à 1 pour la loi ajoutée
            $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=1 WHERE parametre = :param AND paramVal = :pVal");
            $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $pValSucces = $result->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
            $result->execute();
            $nbLigne = $result->rowCount();

            //On met mis en place à -1 pour les autres lois qui concernent le même critère
            $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=-1 WHERE parametre = :param AND paramVal != :pVal");
            $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $pValSucces = $result->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
            $result->execute();
            $nbLigne += $result->rowCount();

            //Voter une loi influe sur les relations avec les différents ordres
            $result2 = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE parametre = :param AND paramVal = :pVal");
            $paramSucces2 = $result2->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $pValSucces2 = $result2->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
            $result2->execute();
            $noblesseVote;
            $clergeVote;
            $TeVote;
            //Chaque ordre gagne ou perd de la satisafction au pouvoir
            foreach ($result2 as $row2) {
              $noblesseVote = $row2['noblesseVoter'];
              $clergeVote = $row2['clergeVoter'];
              $TeVote = $row2['tiersEtatVoter'];

              //Choisir le prochain évènement en fonction de la loi
              $_SESSION['numEvent'] = $row2['idEventAssocie'];
            }
            //appel de la fonction qui met à jour les jauges
            $this->majJaugesLois($noblesseVote,$clergeVote,$TeVote);
            $this->tourSuivantLois();
            //Mettre à jour l'action réaliser
            $_SESSION['action'] = 'voter';
            include '../pagesDeTests/testMajHeritiers.php';
            
            //on renvoie le nb de lignes modifiées dans la base
            return $nbLigne;
        }
        //Si les relations du joueur sont trop mauvaises on lui affiche un message lui expliquant qu'il ne peut pas agir sur les lois
        else{
            $_SESSION['message'] = "L'insatisfaction du peuple vous empêche de modifier les lois";
            return 0;
        }
    }

    /**
    *\fn public function suppLoi() : int
    * \brief Met à jour dans la bdd la loi qui a été supprimée ainsi que toutes celles de la même catégorie
    * \return Renvoie le nombre de lignes modifiées. 0 en cas d'erreur.
    */
    public function suppLoi() : int {
        //On test si le joueur a le droit de mettre en place une loi
        $droit = $this->verifRelation();
        if ($droit){

            $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=0 WHERE parametre = :param");
            $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $result->execute();
            $nbLigne = $result->rowCount();

            //Abroger une loi influe sur les relations avec les différents ordres
            $result2 = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE parametre = :param AND paramVal = :pVal");
            $paramSucces2 = $result2->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $pValSucces2 = $result2->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
            $result2->execute();
            $noblesseAbroge;
            $clergeAbroge;
            $TeAbroge;
            //Chaque ordre gagne ou perd de la satisafction au pouvoir
            foreach ($result2 as $row2) {
              $noblesseAbroge = $row2['noblesseAbroger'];
              $clergeAbroge = $row2['clergeAbroger'];
              $TeAbroge = $row2['tiersEtatAbroger'];

              //Choisir le prochain évènement en fonction de la loi
              $_SESSION['numEvent'] = $row2['idEventAssocieAbroger'];
            }
            //appel de la fonction qui met à jour les jauges
            $this->majJaugesLois($noblesseAbroge,$clergeAbroge,$TeAbroge);
            $this->tourSuivantLois();
            //Mettre à jour l'action réaliser
            $_SESSION['action'] = 'abroger';
            include '../pagesDeTests/testMajHeritiers.php';
            //on renvoie le nb de lignes modifiées dans la base
            return $nbLigne;
        }
        //Si les relations du joueur sont trop mauvaises on lui affiche un message lui expliquant qu'il ne peut pas agir sur les lois
        else{
            $_SESSION['message'] = "L'insatisfaction du peuple vous empêche de modifier les lois";
            return 0;
        }
    }

    public function passerLoi() : void {
        //On met à jour la jauge de l'ordre ayant une affinité avec roi actuel 
        $affinite;
        $result = MyPDO::pdo()->prepare("SELECT affinite from perso WHERE classe = roi");
        $result->execute();
        foreach ($result as $row){
            $affinite = $row['affinite'];
        }
        $nouveauScoreNoblesse= $_SESSION['noblesse'];
        $nouveauScoreClerge= $_SESSION['clerge'];
        $nouveauScoreTE= $_SESSION['tiersEtat'];
        if($affinite == 'noblesse'){
            $nouveauScoreNoblesse +=10;
        }
        elseif($affinite == 'tiers état'){
            $nouveauScoreTE +=10;
        }
        else{
            $nouveauScoreClerge +=10;
        }
        //On remplace les jauges par les nouvelles valeurs et on verifie qu'on ne dépassse pas 100 qui est le max
        if($nouveauScoreClerge > 100){
            $nouveauScoreClerge = 100;
        }
        if($nouveauScoreNoblesse > 100){
            $nouveauScoreNoblesse = 100;
        }
        if($nouveauScoreTE > 100){
            $nouveauScoreTE = 100;
        }
        $_SESSION['noblesse'] = $nouveauScoreNoblesse;
        $_SESSION['clerge'] = $nouveauScoreClerge;
        $_SESSION['tiersEtat'] = $nouveauScoreTE;
        $_SESSION['action'] = 'Passer';
        $this->tourSuivantLois();
        include '../pagesDeTests/testMajHeritiers.php';
    }

    public function tourSuivantLois() : void {
        //Puis on passe à la section suvante, 5 ans plus tard
        $_SESSION['annee'] = $_SESSION['annee'] +5;
        //On test si le joueur a gagné
        if ($_SESSION['annee'] >= 1789){
            $_SESSION['jeu'] = 'gagne';
            $_SESSION['messageFin'] = "Vous avez réussi à garder votre lignée sur le trône jusqu'à l'inévitable révolution française. Félicitation, vous avez gagné !";
            header('Location: fin.php');
            exit();
        }
        //Les personnages vieilissent car les années passent
        require_once '../accesBDD/classesPHP/Personnage.php';
        $perso = new Personnage();
        $perso->vieillirPerso();

        //Chaque tour de jeu il y a des morts et une à 5 naissance(s)
        $nbNaissance = rand(1,5);
        for ($i=0; $i < $nbNaissance; $i++) {
            $perso->creerPersonnage();
        }
        $perso->mortPerso();
    }

    /**
    *\fn public function verifRelation() : bool
    * \brief Vérifie que le joueur a le droit d'influer sur les lois en fonction de sa relation avec les différents ordres.
    * \return Renvoie vrai s'il peut modifier les lois, sinon faux.
    */
    public function verifRelation() : bool {
      $res = true;

      //De plus la moyenne des relations avec les 3 ordres doit être supérieur à 30/100
      $moyenne = ($_SESSION['noblesse'] + $_SESSION['clerge'] + $_SESSION['tiersEtat'])/3;
        //Pour faire passer un changement de loi le joueur doit avoir des relations supérieurs à 20/100 avec les ordres en moyenne
        if($moyenne < 20){
            $res = false;
        }
        else{
            $res = true;
        }
        return $res;
    }

    public function majJaugesLois(int $noblesseChangement, int $clergeChangement, int $TeChangement) : void{
        //Chaque ordre gagne ou perd de la satisafction au pouvoir
        $nouveauScoreNoblesse= $_SESSION['noblesse'] + $noblesseChangement;
        $nouveauScoreClerge= $_SESSION['clerge'] + $clergeChangement;
        $nouveauScoreTE= $_SESSION['tiersEtat'] + $TeChangement; 
        
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
    *\fn public function  getParametre() : string
    * \brief Accesseur sur la catégorie de la loi.
    * \return Renvoie la catégorie de la loi.
    */
    public function getParametre() : string {
        return $this->_parametre;
    }

    /**
    *\fn public function  getParamVal()
    * \brief Accesseur sur la caractéristique de la loi.
    * \return Renvoie la caractéristique de la loi.
    */
    public function getParamVal() {
        return $this->_paramVal;
    }

}
