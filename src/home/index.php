<?php
  //Démarrer la session
  session_start();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Bienvenue dans le Jeu de Lois </title>
    <link rel="stylesheet" href="../css/fondLogin.css">
  </head>

  <body>

    <header>
    </header>

    <main>
        <h1>Bienvenue dans le jeu de lois ! </h1><br>
        <form action="menu.php" method="POST" name="formLogin">
          <input type="submit" name="demarage" value="Démarrer votre règne">
          <input type="submit" name="tutoriel" value="Tutoriel">
          <input type="submit" name="aide" value="Aide">
        </form>
    </main>

  </body>


</html>
