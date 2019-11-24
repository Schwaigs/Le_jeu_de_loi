<?php
  //Démarrer la session
  session_start();

  //Redirection si pas méthode POST
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
  {
      header('Location: ../pageDeLancement/lancement.php');
      exit();
  }

  //Si l'évènement correspond à un choix
  if (isset($_SESSION['choix'])){

    //Si le joueur n'a pas fait de choix
    if (!isset($_POST['choix'])){
      header('Location: ../pageDeLancement/lancement.php');
      exit();
    }

    //Si l'utilisateur à répondu OUI
    if ($_POST['choix'] == 'oui') {

      $_SESSION['argent'] +=  $_SESSION['choix']['argent'];
      $_SESSION['satisfaction'] +=  $_SESSION['choix']['satisfaction'];
    }

    //Si l'utilisateur à répondu NON
    else {
      $_SESSION['argent'] +=  $_SESSION['choix']['argentNON'];
      $_SESSION['satisfaction'] +=  $_SESSION['choix']['satisfactionNON'];
    }
  }

  //Enlever la variable pour savoir si le prochain évènement est  à choix ou non
  unset($_SESSION['choix']);
  $_SESSION['argent'] += 10;
  if ($_SESSION['satisfaction'] > 100){
    $_SESSION['satisfaction'] = 100;
  }

  //Les personnages vieilissent car les années passent
  $_SESSION['annee'] = $_SESSION['annee'] + 3;
  require_once '../accesBDD/classesPHP/Personnage.php';
  $perso = new Personnage();
  $perso->vieillirPerso();

  //On peut passer à la suite
  $_SESSION['suivant'] = true;
  if (isset($_SESSION['idCarac'])){
     header('Location: ../pageDeLancement/lancement.php?id=' . $_SESSION['idCarac']);
  }
  header('Location: ../pageDeLancement/lancement.php');
  exit();
