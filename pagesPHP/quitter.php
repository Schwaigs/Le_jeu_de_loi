<?php
  //Démarrer la session pour pouvoir lancer d'autres instructions qui l'utilisent
  session_start();
  //supprime la session du joueur
  session_destroy();
  //renvoie sur la page d'acceuil du site
  header('Location: ../pageDeLancement/acceuil.php');
  exit();
  ?>
