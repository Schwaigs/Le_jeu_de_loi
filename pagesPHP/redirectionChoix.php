<?php
  //Démarrer la session
  session_start();

  //Si le joueur choisit un evenement
  if (isset($_POST['lois'])){
    $_SESSION['decision'] = 'lois';
  }
  //Si le joueur choisit d'agir sur les lois
  else {
    $_SESSION['decision'] = 'events';
  }

  //Le contenu de la zone de choix sera remplacée selon le choix du joueur
  header('Location: ../pageDeLancement/lancement.php');
  exit();

 ?>
