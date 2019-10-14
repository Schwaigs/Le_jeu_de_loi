<?php

include_once '../Personnage.php';
include_once '../Loi.php';


class Controller {

    private $heritiers;
    private $prochainId;
    private $listePerso;
    private $listeLois;

    function getHeritiers() : array {
        return $this->$heritiers;
    }

    function setHeritiers(array $newHeritiers) : void {
        $this->heritiers = $newHeritiers;
    }

    function getProchainId() : int {
        return $this->prochainId;
    }

    function setProchainId(int $newProchainId) : void {
        $this->prochainId = $newProchainId;
    }

    function getListePerso() : array {
        return $this->listePerso;
    }

    function setListePerso(array $newListePerso) : void {
        $this->listePerso = $newListePerso;
    }

    function getListeLois() : array {
        return $this->listeLois;
    }

    function setListeLois(array $newListeLois) : void {
        $this->listeLois = $newListeLois;
    }

    function creerPersonnage(string $religion, string $nationnalite, int $age,
                            boolval $sexe, string $etatSante, int $parent, boolval $roi)
    {
        /*on recupère le prochain id et on le met à jour*/
        $id = $this->getProchainId();
        $this->setProchainId($id+1);
        /*on cherche son ordre de naissance */
        $ordreDeNaissance = $this->chercherOrdreNaissance($parent);
        /* appel au constructeur de personnage*/
        Personnage->__construct($id,$religion,$nationnalite,$ordreDeNaissance,$age,$sexe,$etatSante,$parent,$roi);
    }

    function creerLoi(string $parametre, $paramVal, string $description) {
        Loi->__construct($parametre,$paramVal,$description);
    }

    function chercherHeritier () : array {
        /*on parcours la collection de nos personnage*/
        foreach ($this->listePerso as $perso) {
            boolval $correspond = true;
            /*pour chacun on regarde ses attributs et on les compare à ceux définis par les lois*/
            foreach ($this->listeLois as $loi) {
                /*si un des attribut ne correspond pas*/
                $attribut = 'get' . $loi->parametre .'()';
                if ($perso->$attribut !== $loi->paramVal){
                    /*alors le personnage ne peut pas etre un heritier du roi actuel*/
                    $correspond = false;
                }
            }
            /*si le personnage valide tout les parametre on l'ajoute à la collection des héritiers*/
            $listeHeritiers = $this->getHeritiers();
            $listeHeritiers[] = $perso->getId();
            $this->setHeritiers($listeHeritiers);
        }
        /*à la fin de la recherche on renvois le tableau des heritiers*/
        return $this->getHeritiers();
    }

    function cherchePersoAvecId ( int $id ) : Personnage {
        $personnage;
        /*on parcours notre collection de personnage*/
        foreach ($this->listePerso as $perso) {
            /*si l'id correspond c'est le bon personnage*/
            if ($perso->getId() == $id){
                $personnage = $perso;
            }
        } 
        return $personnage;
    }

    function choisiRoi (array $heritiers) : Personnage {
        /*voir si on met a jour le roi dans cette fonction*/
        $nbHéritiers = count($heritiers);
        /*si on a un seul id dans le tableau des héritiers alors c'est lui le roi*/
        if ($nbHéritiers == 1) {
            $idRoi = $this->listePerso[0];
            $roi = $this->cherchePersoAvecId($idRoi);
            return $roi;
        }
        /*si aucun heritier le joueur a perdu*/
        if ($nbHéritiers == 0){
          /* comment le mettre en place ?*/
        }
        /*si plusieurs héritiers le choix se fait aléatoirement*/
        if ($nbHéritiers > 1){
            /*on tire un nombre aléatoire qui representera l'index de l'héritier choisit*/
            $indexAlea = rand( 0, $nbHéritiers-1);
            $idRoi = $nbHéritiers[$indexAlea];
            $roi = $this->cherchePersoAvecId($idRoi);
            return $roi;
        }
      }

    function chercherOrdreNaissance(Personnage $parent) : int {
        /*representera toujours le nombre de frere et soeurs né avant notre nouveau perso*/
        $nbfrereEtSoeurs = 0;
        /*on parcours notre liste de personnages*/
        foreach ($this->listePerso as $personnage) {
            /*s'ils ont les memes parents alors ce sont de frère et soeurs */
            if ($personnage->getParent() == $parent){
                $nbfrereEtSoeurs++;
            }
        }
        return $nbfrereEtSoeurs+1;
    }

    function mortPerso (Personnage $perso){
        $perso->setEstEnVie(false);
    }

}
