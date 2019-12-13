<?php

require_once 'MyPDO.php';

/**
*\fn function initBase() : void
* \brief Initialise la base de donnée lors du lancement d'une nouvelle partie.
* Crée une table lois et une table personnages pour le numéro de l'utilisateur passé en paramètre
*/
function initBase($id) : void {

    try{
      $resultLois = MyPDO::pdo()->prepare("
      CREATE TABLE `loisDe" . $id ."` (
       `parametre` varchar(255) NOT NULL,
       `paramVal` varchar(255) NOT NULL,
       `description` varchar(512) CHARACTER SET utf8 NOT NULL,
       `misEnPlace` tinyint(1) NOT NULL,
       `label` varchar(256) CHARACTER SET utf8 NOT NULL,
       `clergeVoter` int(5) NOT NULL,
       `noblesseVoter` int(5) NOT NULL,
       `tiersEtatVoter` int(5) NOT NULL,
       `clergeAbroger` int(5) NOT NULL,
       `noblesseAbroger` int(5) NOT NULL,
       `tiersEtatAbroger` int(5) NOT NULL,
       `idEventAssocie` int(5) NOT NULL,
       `idEventAssocieAbroger` int(5) NOT NULL,
       PRIMARY KEY (`parametre`,`paramVal`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

      $execution = $resultLois->execute();

      echo 'exec create : ' . var_dump($execution) . '<br>';

    }
    catch( Exception $e ) {
        echo 'Erreur : '.$e->getMessage();
        exit;
    }


    try{

      $resultInsert = MyPDO::pdo()->prepare(
          "INSERT INTO loisDe". $id ." VALUES
          ('ordreNaissance', '0', 'Priorité des droits de succession et d’héritage du benjamin par rapport aux autres frères et sœurs. ', 0, 'Ultimogéniture\r\n', 0, 0, 0, -5, -5, -5, 100, 200),
          ('ordreNaissance', '1', 'Priorité des droits de succession et d’héritage de l\'aîné par rapport aux autres frères et sœurs. ', 0, 'Primogéniture', 0, 0, 0, -5, -5, -5, 101, 201),
          ('religion', 'autre', 'Croyance en quelque divinité que ce soit autre que celle des protestants et catholiques ou absence de croyance.', 0, 'Religion tierce', -75, -10, -10, 20, 5, 5, 102, 202),
          ('religion', 'catholique', 'Religion chrétienne dans laquelle le pape exerce l\'autorité en matière de dogme et de morale.', 0, 'Catholicisme', 20, 5, 5, -50, -15, -15, 103, 203),
          ('religion', 'protestant', 'Religion chrétienne fondée sur l\'enseignement, la personne et la vie de Jésus de Nazareth, qui rejette l\'autorité du pape.', 0, 'Protestantisme', -50, -5, -5, 10, 0, 0, 104, 204),
          ('richesse', '0', 'Exclusion des personnes ayant un fort patrimoine de la succession au trône.', 0, 'Pauvreté', 0, -20, 5, 0, 10, -5, 105, 205),
          ('richesse', '1', 'Exclusion des personnes ayant un faible patrimoine de la succession au trône.', 0, 'Richesse', 0, 10, -5, 0, -25, 0, 106, 206),
          ('sante', '1', 'Priorité des droits de succession et d’héritage aux personnes en bonne santé. ', 0, 'Sanité', 0, 10, -10, 0, -10, 10, 109, 209),
          ('sexe', 'femme', 'Exclusion des hommes de la succession au trône.', 0, 'Féminité', -15, 0, 0, 5, -5, -5, 107, 207),
          ('sexe', 'homme', 'Exclusion des femmmes de la succession au trône.', 0, 'Masculinité', 5, 0, 0, -10, -5, 0, 108, 208)"
      );

      $execution2 = $resultInsert->execute();

      echo 'exec insert : ' . var_dump($execution2) . '<br>';

    }
    catch( Exception $e ) {
        echo 'Erreur : '.$e->getMessage();
        exit;
    }

    echo '<br>------------------------<br>';

    try{
      $resultPerso = MyPDO::pdo()->prepare("
      CREATE TABLE `persoDe" . $id ."` (
          `id` int(11) NOT NULL,
          `prenom` varchar(256) CHARACTER SET utf8 NOT NULL,
          `religion` varchar(256) CHARACTER SET utf8 NOT NULL,
          `nationnalite` varchar(256) CHARACTER SET utf8 NOT NULL,
          `ordreNaissance` int(11) NOT NULL,
          `age` int(11) NOT NULL,
          `sexe` varchar(256) CHARACTER SET utf8 NOT NULL,
          `etatSante` varchar(256) CHARACTER SET utf8 NOT NULL,
          `parent` int(11) DEFAULT NULL,
          `classe` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
          `richesse` int(1) NOT NULL,
          `affinite` varchar(256) CHARACTER SET utf8 NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

      $execution20 = $resultPerso->execute();

      echo 'exec create : ' . var_dump($execution20) . '<br>';

    }
    catch( Exception $e ) {
        echo 'Erreur : '.$e->getMessage();
        exit;
    }


    try{

      $resultInsertP = MyPDO::pdo()->prepare(
          "INSERT INTO persoDe". $id ." VALUES
          (1, 'Enguerrand', 'catholique', 'France', 1, 45, 'homme', 'bon', NULL, 'roi', 1, 'clergé'),
          (2, 'Aurore', 'catholique', 'France', 1, 25, 'femme', 'bon', 1, 'heritier', 1, 'noblesse'),
          (4, 'Jeanne', 'protestant', 'France', 2, 21, 'femme', 'moyen', 1, 'heritier', 0, 'tiers état'),
          (5, 'Robin', 'catholique', 'France', 3, 20, 'homme', 'bon', 1, 'heritier', 1, 'clergé'),
          (6, 'Mélusine', 'catholique', 'Étranger', 1, 6, 'femme', 'bon', 2, 'nonHeritier', 1, 'tiers état'),
          (7, 'Perceval', 'autre', 'France', 2, 3, 'homme', 'faible', 2, 'nonHeritier', 0, 'noblesse'),
          (8, 'Clotaire', 'catholique', 'France', 1, 5, 'homme', 'bon', 5, 'nonHeritier', 1, 'tiers état'),
          (9, 'Guenièvre', 'catholique', 'France', 2, 2, 'femme', 'bon', 5, 'nonHeritier', 0, 'clergé'),
          (12, 'Yseult', 'catholique', 'France', 4, 10, 'femme', 'moyen', 1, 'heritier', 0, 'clergé'),
          (17, 'Cunégonde', 'catholique', 'Étranger', 3, 0, 'femme', 'bon', 5, 'nonHeritier', 1, 'noblesse'),
          (25, 'Amaury', 'catholique', 'France', 3, 1, 'homme', 'bon', 2, 'nonHeritier', 0, 'tiers état')
          ");
      $execution21 = $resultInsertP->execute();

      echo 'exec insert : ' . var_dump($execution21) . '<br>';

    }
    catch( Exception $e ) {
        echo 'Erreur : '.$e->getMessage();
        exit;
    }
}
