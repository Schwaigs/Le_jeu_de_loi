<?php
  //Démarrer la session pour pouvoir lancer d'autres instructions qui l'utilisent
  session_start();

    $_SESSION['numTuto']++;

  //renvoie sur la page d'acceuil du site
  header('Location: ../home/tuto.php');
  exit();
  ?>
