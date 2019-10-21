<?php
  //Démarrer la session
  session_start();

  if (!isset($_SESSION['login']) || empty($_SESSION['login'])){
      header('Location: testLogin.php');
      exit();
  }

  if (!isset($_SESSION['numEvent'])){
      $_SESSION['numEvent'] = 1;
  }


  ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Titre non défini </title>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div style="flex-grow: 1">Année</div>
        <div style="flex-grow: 8">Titre</div>
        <div style="flex-grow: 1">Aide/Encyclopédie</div>
      </div>
    </header>

    <main>
      <div class="main">
        <div class="container" id="arbre">
          <p>Première phrase</p>
        </div>
        <div class="container" id="event">
          <p>Première phrase</p>
          <?php
          require_once '../accesBDD/bddT3.php';

          //   Créez un objet PDO en utilisant les informations contenues dans bdd.php
          try {
            $pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
          }
          catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
          }
          //  Construisez et exécute une requête préparée
          $result = $pdo->prepare(
              "SELECT * FROM evenements WHERE id=:id"
          );

          $id = $result->bindValue(':id',htmlspecialchars($_SESSION['numEvent']), PDO::PARAM_INT);

          // 4. On exécute la requête $result
          $ok2 = $result->execute();


          if (0 == $result->rowCount())
          {
            echo 'nope';
          }
            /*Afficher la liste des lois que l'utilistateur peut choisir */
            foreach ( $result as $row ) {
                echo $row['texte'] . '<br><br>';
                $_SESSION['numEvent'] ++;
            }
            ?>

          <form action="testChoixEvent.php" method="POST" name="formEvent">
            Quelles lois voulez-vous mettre en place ? <br><br>
            <?php

            require_once '../accesBDD/MyPDO.php';
            require_once '../accesBDD/classesPHP/CtrlLoi.php';
                $ctrlLoi = new CtrlLoi();
                try{
                    $ctrlLoi->afficheLoiPeutVoter();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }

            ?>
            <br>
            <br>
            <input type="submit" value="Voter">
          </form>
        </div>
        <div class="column">
          <div id="lois">
            Liste des lois en place : <br><br>
            <?php
            require_once '../accesBDD/MyPDO.php';
            require_once '../accesBDD/classesPHP/CtrlLoi.php';
                $ctrlLoi = new CtrlLoi();
                try{
                    $ctrlLoi->afficheLoiEnPlace();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }

              ?>
          </div>
          <div id="carac">
            <p>Ceci est une caractéristique</p>

          </div>
        </div>
      </div>
    </main>

  </body>


</html>
