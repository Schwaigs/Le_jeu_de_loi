<?php
session_start();
if (!isset($_SESSION['numTuto'])){
    /*
    * \var suivant est une variable de session qui permet de savoir si le joueur a déjà choisit entre un événement ou un vote.
    */
    $_SESSION['numTuto'] = 1;
}
if ($_SESSION['numTuto']>6) {
  $_SESSION['numTuto']=1;
}
 ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Jeu de lois - Tutoriel</title>
    <link rel="stylesheet" href="../css/tuto.css">
    <script >
      function avancerTuto() {
        document.body.style.backgroundImage = "url('../../img/tuto".$_SESSION['numTuto'].".png')";
        <?php
        $_SESSION['numTuto']++;
         ?>
      }
    </script>
  </head>
  <body>
    <main>

  </main>
  </body>
  <div class="boutonAvancer">
    <form action="../game/avanceTuto.php" method="post">
      <?php
          echo "<img src=../../img/tuto".$_SESSION['numTuto'].".png>";

      ?>
      <input type="submit" onclick="avancerTuto()" value="Suivant">
    </form>
  </div>
  <a href="index.php">Retour à l'acceuil</a>
</html>
