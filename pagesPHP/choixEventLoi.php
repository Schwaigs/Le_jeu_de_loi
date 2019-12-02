
<?php
require_once '../accesBDD/classesPHP/CtrlLoi.php';

  //Si le joueur n'a pas encore fait de choix et qu'il peut encore voter une loi
  if ($_SESSION['nbLois'] > 0 && !isset($_SESSION['decision'])){ ?>
    <p> Quel type de décisions voulez-vous prendre ? </p>
    <form id="formEvent" action="../pagesPHP/redirectionChoix.php" method="POST" name="fromChoix">
    <input type="submit" name="lois" value="Lois">
    <input type="submit" name="events" value="Events">

    <?php
  }
  else if (!isset($_SESSION['decision'])){
    $_SESSION['decision'] = 'events';
    header('Location: ../pagesPHP/redirectionChoix.php');
  }
  else {
    echo 'Nombre de lois restantes : ' . $_SESSION['nbLois'];
    //Si il décide de modifier les lois
    if ($_SESSION['decision'] == 'lois'){
      include '../pagesPHP/ajoutRetireLoi.php';
    }
    else {

        //Choisir un évènement en fonction des jauges les plus basses
        if ($_SESSION['noblesse'] < $_SESSION['tiersEtat'])
        {
          if ($_SESSION['clerge'] < $_SESSION['noblesse'])
          {
            //Clergé le plus mécontent
            $ordre = 'clerge';
          }
          else{
            //Noblesse la plus mécontente
            $ordre = 'noblesse';
          }
        }
        else {
          if ($_SESSION['clerge'] < $_SESSION['tiersEtat'])
          {
            //Clergé le plus mécontent
            $ordre = 'clerge';
          }
          else{
            //Tiers-état le plus mécontent
            $ordre = 'tiersEtat';
          }
        }


          //On cherche cet évènement dans la base
          $result = MyPDO::pdo()->prepare("SELECT * FROM newEvents WHERE categorie = :ordre");
          $parametrage = $result->bindValue(':ordre', $ordre, PDO::PARAM_STR);
          $execution = $result->execute();

          //On compte le nombre d'évènements possibles
          $nbEvents = $result->rowCount();
          $i = 0;
          if (!isset($_SESSION['numEvent'])) {
              $_SESSION['numEvent'] = rand(1,$nbEvents);
          }


        foreach ( $result as $row ) {
          $i++;
          //Si on a l'évènement tiré au hasard
          if ($i == $_SESSION['numEvent']){
            //On ajoute le texte de l'évènement
            $_SESSION['texteEvent'] =  '<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">'
                                      . $row['texte'] . '<br>';

            //Stocker l'évènement temporairement
            $_SESSION['choix'] = $row;
            if ($row['choix'] == 1){
              $_SESSION['choixAFaire'] = true;
              //Si un choix est à faire, mettre en place les réponses possibles
              $_SESSION['texteEvent'] =  $_SESSION['texteEvent']  .
               '<input type="radio" name="choix" value="oui"> Oui <br>
                <input type="radio" name="choix" value="non"> Non <br>';
            }
            else {
              $_SESSION['choixAFaire'] = false;
              $_SESSION[$row['ordreConcerneOui']] = $_SESSION[$row['ordreConcerneOui']]  + ($row['actionOui']);
            }

            $_SESSION['texteEvent'] =  $_SESSION['texteEvent']  . '<input type="submit" value="Tour suivant"></form>';

            //Afficher le texte
            echo $_SESSION['texteEvent'];
          }

        }
      }
    }
  ?>
