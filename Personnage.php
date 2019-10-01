<?php 

class Personnage {
    private $id;
    private $religion;
    private $nationnalite;
    private $ordreNaissance;
    private $age;
    private $sexe;
    private $etatSante;
    private $estEnVie = true;
    private $parent;
    private $roi;
    
    function __construct( int $id, string $religion, string $nationnalite, int $ordreNaissance,
                          int $age, boolval $sexe, string $etatSante, int $parent, boolval $roi)
    {
        $this->id = $id;
        $this->religion = $religion;
        $this->nationnalite = $nationnalite;
        $this->ordreNaissance = $ordreNaissance;
        $this->age = $age;
        $this->sexe = $sexe;
        $this->etatSante = $etatSante;
        $this->parent = $parent;
        $this->roi = $roi;
    }

    function getId() : int {
        return $this->id;
    }

    function setId(int $newId) : void {
        $this->id = $newId;
    }

    function getReligion() : string {
        return $this->religion;
    }

    function setReligion(string $newReligion) : void {
        $this->religion = $newReligion;
    }

    function getNationnalite() : string {
        return $this->nationnalite;
    }

    function setNationnalite(string $newNationnalite) : void {
        $this->nationnalite = $newNationnalite;
    }

    function getOrdreNaissance() : int {
        return $this->ordreNaissance;
    }

    function setOrdreNaissance(int $newOrdreNaissance) : void {
        $this->ordreNaissance = $newOrdreNaissance;
    }

    function getAge() : int {
        return $this->age;
    }

    function setAge(int $newAge) : void {
        $this->age = $newAge;
    }

    function getSexe() : boolval {
        return $this->sexe;
    }

    function setSexe(boolval $newSexe) : void {
        $this->sexe = $newSexe;
    }

    function getEtatSante() : string {
        return $this->etatSante;
    }

    function setEtatSante(string $newEtatSante) : void {
        $this->etatSante = $newEtatSante;
    }

    function getEstEnVie() : boolval {
        return $this->estEnVie;
    }

    function setEstEnVie(boolval $newEstEnVie) : void {
        $this->estEnVie = $newEstEnVie;
    }

    function getParent() : int {
        return $this->parent;
    }

    function setParent(int $newParent) : void {
        $this->parent = $newParent;
    }

    function getRoi() : boolval {
        return $this->roi;
    }

    function setRoi(boolval $newRoi) : void {
        $this->roi = $newRoi;
    }
}