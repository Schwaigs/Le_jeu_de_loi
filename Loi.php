<?php

class Loi {

    private $parametre;
    private $paramVal;
    private $description;

    function __construct( string $parametre, $paramVal, string $description){
        $this->parametre = $parametre;
        $this->paramVal = $paramVal;
        $this->description = $description;
    }

    function getParametre() : string {
        return $this->parametre;
    }

    function setParametre(string $newParametre) : void {
        $this->parametre = $newParametre;
    }
    
    function getParamVal() /*voir quoi mettre quand pas de type de retour*/ {
        return $this->paramVal;
    }

    function setParamVal($newParamVal) : void {
        $this->paramVal = $newParamVal;
    }
    
    function getDescription() : string {
        return $this->description;
    }

    function setDescription(string $newDescription) : void {
        $this->description = $newDescription;
    }

}