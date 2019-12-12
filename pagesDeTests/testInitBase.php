<?php
require_once '../accesBDD/initBase.php';
session_start();
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="arbreGenealogique.css">
        <title>Page init Base</title>
    </head>
    <body>

    <?php
      //Numéro associé à un utilistateur
      $_SESSION['login'] = rand(1,10000000);
      $succes = initBase($_SESSION['login']);
    ?>

    </body>
</html>
