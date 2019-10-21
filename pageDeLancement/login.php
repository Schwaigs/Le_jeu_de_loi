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
      <form action="demarage.php" method="POST" name="formLogin">
        Quel sera votre nom de souverain ? <br><br>
        <input type="text" name="login"> <br>
        <input type="submit" value="Commencer">
      </form>
    </main>

  </body>


</html>
