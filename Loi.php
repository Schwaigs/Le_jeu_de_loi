<?php

require_once './bddT3.php';
require_once './MyPDO.php';

class Loi {
    private $_parametre;
    private $_paramVal;

    public function __construct(string $parametre, string $paramVal){
        $this->_parametre = $parametre;
        //fonctionnement un peu différent pour la parametre ordre de naissance 
        if($parametre == 'ordreNaissance'){
            if($paramVal == 'jeune'){
                $this->_paramVal= -1;
            }
            else{
                $this->_paramVal= 1;
            }
        }

        else{
            $this->_paramVal= $paramVal;
        }       
    }

    public function ajoutLoi() : int {
        $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=1 WHERE parametre = :param AND paramVal = :pVal");
        $paramSucces = $result->bindValue(':param',$this->getParametre(), PDO::PARAM_STR);
        $pValSucces = $result->bindValue(':pVal',$this->getParamVal(), PDO::PARAM_STR);
        $result->execute();
        $nbLigne = $result->rowCount();
        //on renvoie le nb de lignes modifiées dans la base
        return $nbLigne;
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