
<?php
require_once '../accesBDD/classesPHP/CtrlLoi.php';

  //Le schéma se résume à : 1 étape de vote de lois puis un évènement
  //en fonction du choix pris
  if ($_SESSION['action'] == 'lois') {

    //On a le choix entre voter une loi, l'abroger ou ne rien faire
    include '../pagesPHP/ajoutRetireLoi.php';
  }
  else {
    echo 'numEvent = ' . $_SESSION['numEvent'];
    /*Il y a 3 évènements différents qui peuvent apparaitre : Dernièrement
        - une loi a été votée
        - une loi a été abrogée
        - une loi a été voté                                                */
    $resultEvent;
    if (($_SESSION['action'] != 'voter') && ($_SESSION['action'] != 'abroger')) {
      //Choisir un évènement en fonction des jauges de relations les plus basses
      if ($_SESSION['noblesse'] < $_SESSION['tiersEtat']){
        if ($_SESSION['clerge'] < $_SESSION['noblesse']){
          $ordre = 'clerge'; //Clergé le plus mécontent
        }
        else{
          $ordre = 'noblesse'; //Noblesse la plus mécontente
        }
      }
      else {
        if ($_SESSION['clerge'] < $_SESSION['tiersEtat']){
          $ordre = 'clerge'; //Clergé le plus mécontent
        }
        else{
          $ordre = 'tiersEtat'; //Tiers-état le plus mécontent
        }
      }

      //On cherche cet évènement dans la base
      $resultEvent = MyPDO::pdo()->prepare("SELECT * FROM newEvents WHERE categorie = :ordre");
      $parametrage = $resultEvent->bindValue(':ordre', $ordre, PDO::PARAM_STR);
      $execution = $resultEvent->execute();

      //On compte le nombre d'évènements possibles
      $nbEvents = $resultEvent->rowCount();

      //Si l'évènement n'a pas encore été changer
      if (!isset($_SESSION['numEvent'])) {
          $nEvent = rand(1,$nbEvents);
          $i = 0;
          foreach ( $resultEvent as $row ) {
            if ($nEvent == $i) {
              $_SESSION['numEvent'] = $row['id'];
            }
          }
      }

    }
    else if ($_SESSION['action'] == 'voter') {
      //La loi voter délanche un évènement spécial
      //On cherche cet évènement précis dans la base
      $resultEvent = MyPDO::pdo()->prepare("SELECT * FROM newEvents WHERE id = :idEvent");
      $parametrage = $resultEvent->bindValue(':idEvent', $_SESSION['numEvent'], PDO::PARAM_INT);
      $execution = $resultEvent->execute();
    }
    else if ($_SESSION['action'] == 'abroger') {
      /*Abroger une lois est très couteux niveau relation, en effet l'ordre
        concerné se sentira trahi, sa jauge a baissé drastiquement et un
        évènement explique la situation                                     */
        //On cherche cet évènement précis dans la base
      $resultEvent = MyPDO::pdo()->prepare("SELECT * FROM newEvents WHERE id = :idEvent");
      $parametrage = $resultEvent->bindValue(':idEvent', $_SESSION['numEvent'], PDO::PARAM_INT);
      $execution = $resultEvent->execute();

    }
    else {
      //il doit y avoir une erreur quelque part
      header('Location: 404');
      exit();
    }

    foreach ( $resultEvent as $row ) {
      //Si on a l'évènement tiré au hasard
      if ($row['id'] == $_SESSION['numEvent']){
        //On ajoute le texte de l'évènement
        $_SESSION['texteEvent'] =  '<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">'
                                  . $row['texte'] . '<br>';

        //Stocker l'évènement temporairement
        $_SESSION['choix'] = $row;
        if ($_SESSION['numEvent'] >99) {
          //Passer et n'afficher que le texte
        }
        else if ($row['choix'] == 1){
          $_SESSION['choixAFaire'] = true;
          //Si un choix est à faire, mettre en place les réponses possibles
          $_SESSION['texteEvent'] =  $_SESSION['texteEvent']  .
           '<input type="radio" name="choix" value="oui"> Oui <br>
            <input type="radio" name="choix" value="non"> Non <br>';
        }
        //Sinon le joueurs n'a pas de choix sur ce qu'il va lui arriver
        else {
          $_SESSION['choixAFaire'] = false;
          $_SESSION[$row['ordreConcerneOui']] += ($row['actionOui']);
        }

        $_SESSION['texteEvent'] =  $_SESSION['texteEvent']  . '<input type="submit" value="Tour suivant"></form>';

        //Afficher le texte
        echo $_SESSION['texteEvent'];
      }
    }
  }
