<?php

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
        $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=1 WHERE parametre = :param AND paramVal = :pVal");
        $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
        $pValSucces = $result->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
        $result->execute();
        $nbLigne = $result->rowCount();

        //on met a jour l'arbre
        $this->majCouleurHeritiers();
        //on renvoie le nb de lignes modifiées dans la base
        return $nbLigne;
    }

    public function majCouleurHeritiers(){
        $heritage = new Heritage();
        try{
            //cherche les heritiers possibles apres la mise en place de la loi
            $heritiers = $heritage->chercherHeritier();
            if($heritiers == null){
                echo "vous n'avez aucun héritiers, vous avez perdu";
            }
            else{
                //maj des heritiers et non heritiers dans la base
                $heritage->classePersoHeritier($heritiers);
                $heritage->classePersoNonHeritier($heritiers);
            }
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
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