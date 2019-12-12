
<?php
require_once '../accesBDD/classesPHP/CtrlLoi.php';

  //Le schéma se résume à : 1 étape de vote de lois puis un évènement
  //en fonction du choix pris
  if ($_SESSION['action'] == 'lois') {

    //On a le choix entre voter une loi, l'abroger ou ne rien faire
    include '../pagesPHP/ajoutRetireLoi.php';
  }
  else {
    /*Il y a 3 évènements différents qui peuvent apparaitre : Dernièrement
        - une loi a été votée
        - une loi a été abrogée
        - rien n'a été fait sur les lois, il faut donc prendre un évènement aléatoire*/

    //Si aucune loi n'a été voté ou abrogé recemment
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
            $i++;
            if ($nEvent == $i) {
              $_SESSION['numEvent'] = $row['id'];
            }
          }
      }

    }
    $resultEvent = MyPDO::pdo()->prepare("SELECT * FROM newEvents");
    $execution = $resultEvent->execute();
    
    foreach ( $resultEvent as $row ) {
      //Si on a l'évènement tiré au hasard
      if ($row['id'] == $_SESSION['numEvent']){
        //On ajoute le texte de l'évènement
        $_SESSION['texteEvent'] =  '<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">'
                                  . $row['texte'] . '<br>';

        //Stocker l'évènement temporairement
        $_SESSION['choix'] = $row;
        if ($_SESSION['numEvent'] >99) {
          //Aucun choix à faire mais les relations continuent de changer 
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
