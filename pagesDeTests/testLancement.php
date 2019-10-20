<?php
  //Démarrer la session
  session_start();

  if (!isset($_SESSION['login']) || empty($_SESSION['login'])){
      header('Location: testLogin.php');
      exit();
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
            <input type="submit" value="Voter">
          </form>
        </div>
        <div class="column">
          <div id="lois">
            <p>Ceci est une loi</p>
          </div>
          <div id="carac">
            <p>Ceci est une caractéristique</p>
          </div>
        </div>
      </div>
    </main>

  </body>


</html>
