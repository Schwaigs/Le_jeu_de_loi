<?php

require_once 'MyPDO.php';

/**
*\fn function initBase() : void
* \brief Initialise la base de donnée lors du lancement d'une nouvelle partie.
*/
function initBase() : void {
    /*On vide la table des personnages*/
    $resultVide = MyPDO::pdo()->prepare("TRUNCATE TABLE perso");
    $resultVide->execute();

    /*On initialise la table des personnages*/
    $resultInsert = MyPDO::pdo()->prepare(
        "INSERT INTO perso VALUES
        (1,'Enguerrand','catholique','France',1,45,'homme','bon',null,'roi',1,'clergé'),
        (2,'Aurore','catholique','France',1,25,'femme','bon',1,'heritier',1,'noblesse'),
        (4,'Jeanne','protestant','France',2,21,'femme','moyen',1,'heritier',0,'tiers état'),
        (5,'Robin','catholique','France',3,20,'homme','bon',1,'heritier',1,'clergé'),
        (6,'Mélusine','catholique','Étranger',1,6,'femme','bon',2,'nonHeritier',1,'tiers état'),
        (7,'Perceval','autre','France',2,3,'homme','faible',2,'nonHeritier',0,'noblesse'),
        (8,'Clotaire','catholique','France',1,5,'homme','bon',5,'nonHeritier',1,'tiers état'),
        (9,'Guenièvre','catholique','France',2,2,'femme','bon',5,'nonHeritier',0,'clergé'),
        (12,'Yseult','catholique','France',4,10,'femme','moyen',1,'nonHeritier',0,'clergé'),
        (17,'Cunégonde','catholique','Étranger',3,0,'femme','bon',5,'nonHeritier',1,'noblesse'),
        (25,'Amaury','catholique','France',3,1,'homme','bon',2,'nonHeritier',0,'tiers état')"
    );
    $resultInsert->execute();

    /*On remet a zero la table des lois*/
    $resultLoi = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=0");
    $resultLoi->execute();
}
