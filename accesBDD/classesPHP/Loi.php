<?php
session_start();
require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';
require_once '../accesBDD/classesPHP/Heritage.php';

class Loi {
    private $_parametre;
    private $_paramVal;

    public function __construct(string $parametre,$paramVal){
        $this->_parametre = $parametre;
        $this->_paramVal= $paramVal;
    }

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
        else{
            $_SESSION['message'] = "L'insatisfaction du peuple vous empêche de modifier les lois";
            return 0;
        }
    }

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
        else{
            $_SESSION['message'] = "L'insatisfaction du peuple vous empêche de modifier les lois";
            return 0;
        }
    }
    	
    public function verifRelation() : boolean {
        if($_SESSION['noblesse'] < 10 || $_SESSION['clerge'] < 10 || $_SESSION['tiersEtat'] < 10){
            return false;
        }
        $moyenne = ($_SESSION['noblesse'] + $_SESSION['clerge'] + $_SESSION['tiersEtat'])/3;
        else if($moyenne < 30){
            return false;
        }
        else{
            return true;
        }
    }
    
    public function getParametre() : string {
        return $this->_parametre;
    }

    public function setParametre(string $newParametre) : void {
        $this->_parametre = $newParametre;
    }

    public function getParamVal() {
        return $this->_paramVal;
    }

    public function setParamVal($newParamVal) : void {
        $this->_paramVal = $newParamVal;
    }
}
