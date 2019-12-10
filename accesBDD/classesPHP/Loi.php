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
        $droit = this->verifRelation();
        if ($droit){

            //On met mis en place à 1 pour la loi ajoutée
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

            $heritage = new Heritage();
            try{
            //cherche les heritiers possibles apres la mise en place de la loi
                $heritage->majHeritiersLois();
            }
            catch( PDOException $e ) {
                echo 'Erreur : '.$e->getMessage();
                exit;
            }
            $_SESSION['nbLois']--;

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
        $droit = this->verifRelation();
        if ($droit){

            $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=0 WHERE parametre = :param");
            $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
            $result->execute();
            $nbLigne = $result->rowCount();

            $heritage = new Heritage();
            try{
            //cherche les heritiers possibles apres la mise en place de la loi
               $heritage->majHeritiersLois();
            }
            catch( PDOException $e ) {
                echo 'Erreur : '.$e->getMessage();
                exit;
            }
            $_SESSION['nbLois']--;

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
    *\fn public function verifRelation() : boolean
    * \brief Vérifie que le joueur a le droit d'influer sur les lois en fonction de sa relation avec les différents ordres.
    * \return Renvoie vrai s'il peut modifier les lois, sinon faux.
    */
    public function verifRelation() : boolean {
        //Pour faire passer un changement de loi le joueur doit avoir des relations supérieurs à 10/100 avec chacun des 3 ordres
        if($_SESSION['noblesse'] < 10 || $_SESSION['clerge'] < 10 || $_SESSION['tiersEtat'] < 10){
            return false;
        }
        //De plus la moyenne des relations avec les 3 ordres doit être supérieur à 30/100
        $moyenne = ($_SESSION['noblesse'] + $_SESSION['clerge'] + $_SESSION['tiersEtat'])/3;
        else if($moyenne < 30){
            return false;
        }
        else{
            return true;
        }
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