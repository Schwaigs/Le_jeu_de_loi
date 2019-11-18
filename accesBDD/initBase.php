<?php

require_once 'MyPDO.php';

function initBase() : void {
    /*On vide la table des personnages*/
    $resultVide = MyPDO::pdo()->prepare("TRUNCATE TABLE perso");
    $resultVide->execute();

    /*On initialise la table des personnages*/
    $resultInsert = MyPDO::pdo()->prepare(
        "INSERT INTO perso VALUES
        (1,'catholique','france',1,45,'homme','bon',null,'roi'),
        (2,'catholique','france',1,25,'femme','bon',1,'heritier'),
        (4,'protestant','france',2,21,'femme','moyen',1,'heritier'),
        (5,'catholique','france',3,20,'homme','bon',1,'heritier'),
        (6,'catholique','grande bretagne',1,6,'femme','bon',2,'nonHeritier'),
        (7,'athee','france',2,3,'homme','faible',2,'heritier'),
        (8,'catholique','france',1,5,'homme','bon',5,'heritier'),
        (9,'catholique','france',2,2,'femme','bon',5,'heritier'),
        (12,'catholique','france',4,10,'femme','moyen',1,'heritier'),
        (17,'catholique','suisse',3,0,'femme','bon',5,'nonHeritier'),
        (25,'catholique','suisse',3,1,'homme','bon',2,'nonHeritier')"
    );
    $resultInsert->execute();

    /*On remet a zero la table des lois*/
    $resultLoi = MyPDO::pdo()->prepare("UPDATE lois SET miseEnPlace=0");
    $resultLoi->execute();
}
