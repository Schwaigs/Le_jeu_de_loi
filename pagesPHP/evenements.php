<?php
        require_once '../accesBDD/bddT3.php';
        require_once '../accesBDD/MyPDO.php';


        //Si on réfraichi juste la page, on garde le même évènement
        if ($_SESSION['suivant'] != true){
          echo $_SESSION['texteEvent'];
        }
        else {
          //Effacer l'ancien texte
          $_SESSION['texteEvent'] = '<div id="formEvent">';

          //Faire en sorte que le joueur clique sur suivant pour avancer, rafraichir la page ne sert à rien
          $_SESSION['suivant'] = false;

          //On tire un nombre aléatoirement parmi les évènements possibles
          $idEvent = rand(1, 25);
          $_SESSION['numEvent'] = $idEvent;

          //On cherhce cet évènement dans la base
          $result = MyPDO::pdo()->prepare("SELECT * FROM evenements WHERE id = :idEvent");
          $parametrage = $result->bindValue(':idEvent', $idEvent, PDO::PARAM_INT);
          $execution = $result->execute();

          foreach ( $result as $row ) {
            //On ajoute le texte de l'évènement
            $_SESSION['texteEvent'] =  $_SESSION['texteEvent']  . $row['texte'] . '<br>';

            //On fait une action en fonction du type d'évènement :
            //Naissance / Mort / Choix / Autre
            switch ($row['categorie']) {

                case "Naissance":

                      //Création d'un nouveau né dans la famille
                      require_once '../accesBDD/classesPHP/Personnage.php';
                      $perso = new Personnage();
                      $perso->creerPersonnage();

                      //Chercher le dernier personnage ajouté, il correspond au nouveau né
                      $result2 = MyPDO::pdo()->prepare("SELECT max(id) FROM perso");
                      $execution2 = $result2->execute();
                      $idEnfant;
                      while ( $row2 = $result2->fetch() ) {
                          $idEnfant = $row2[0];
                      }

                      //Afficher ses caractéristiques
                      $infoEnfant = caracPerso($idEnfant);

                          $_SESSION['texteEvent'] = $_SESSION['texteEvent'] .'<br>
                                  <div class="ligne1">
                                      <p>Prénom : '.$infoEnfant['prenom'].'</p>
                                      <p>Parent : '.$infoEnfant['parent'].'</p>
                                      <p>Sexe : '.$infoEnfant['sexe'].'</p>';
                                      if ($infoEnfant['ordreNaissance'] == 1) {
                                        $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . '<p>Ordre de Naissance : 1er de sa fraterie </p>';
                                      }
                                      else {
                                        $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . '<p>Ordre de Naissance : ' . $infoEnfant['ordreNaissance'].'ème de sa fraterie </p>';
                                      }
                                  $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . '
                                  </div>
                                  <div class="ligne2">
                                      <p>Religion : '.$infoEnfant['religion'].'</p>
                                      <p>Pays : '.$infoEnfant['nationnalite'].'</p>
                                      <p>Etat de sante : '.$infoEnfant['etatSante'].'</p>
                                  </div>
                              ';
                        //Si on a cliquer sur suivant, on prépare le formulaire pour passer à l'évènement d'après
                        $_SESSION['texteEvent'] = $_SESSION['texteEvent'] .'<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">';
                        break;


                case "Mort":

                    //Faire mourir des personnages aléatoirement
                    require_once '../accesBDD/classesPHP/Personnage.php';
                    $perso = new Personnage();
                    $nbMorts = $perso->mortPerso();

                    if ($nbMorts == 0){
                      $_SESSION['texteEvent'] =  '<div id="formEvent">';
                      //Si aucune mort, texte différent
                      $_SESSION['texteEvent'] = "Le temps passe...Votre famille vieillit mais la grâce de Dieu maintient votre famille en vie.";
                    }
                    else if ($nbMorts == 1) {
                      //Si 1 mort
                      $_SESSION['texteEvent'] = " personne. Dans le château, les résidents évitent ce sujet.";
                    }
                    else {
                      //Afficher le nombre de morts
                      $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . $nbMorts . " personnes... Une triste pensée se fait ressentir dans tout le château.";

                    }

                    //Si on a cliquer sur suivant, on prépare le formulaire pour passer à l'évènement d'après
                    $_SESSION['texteEvent'] = $_SESSION['texteEvent'] .'<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">';
                    break;


                case "Choix":

                    //Si on a cliquer sur suivant, on prépare le formulaire pour passer à l'évènement d'après
                    $_SESSION['texteEvent'] = $_SESSION['texteEvent'] .'<form action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">';

                    //Stocker le résultat de la requête pour connaitres les choix possibles
                    $_SESSION['choix'] = $row;

                    //Créer un forumlaire pour répondre à une question simple lros d'un évènement
                    $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . ' <input type="radio" name="choix" value="oui"> Oui <br>
                                                                          <input type="radio" name="choix" value="non"> Non <br>';
                    break;
                case "Sans choix":
                    $_SESSION['argent'] +=  $row['argent'];
                    $_SESSION['satisfaction'] +=  $row['satisfaction'];
                    //Si on a cliquer sur suivant, on prépare le formulaire pour passer à l'évènement d'après
                    $_SESSION['texteEvent'] = $_SESSION['texteEvent'] .'<form id="formEvent" action="../pagesPHP/tourSuivant.php" method="POST" name="fromSuivant">';
                    break;
            }
            //Fermer le formualaire et mettre un boutton suivant
            $_SESSION['texteEvent'] = $_SESSION['texteEvent'] . '
                        <input type="submit" value="Tour suivant"">
                    </form></div>';

            //Afficher le texte
            echo $_SESSION['texteEvent'];
          }
        }
