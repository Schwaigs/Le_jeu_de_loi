<?php
  //Démarrer la session
  session_start();

  //Redirection si pas méthode POST
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
  {
      header('Location: ../pageDeLancement/lancement.php');
      exit();
  }

  if (!isset($_SESSION['choix']) || !isset($_POST['choix'])){
      header('Location: ../pageDeLancement/lancement.php');
      exit();
  }

  if ($_POST['choix'] == 'oui') {
    $_SESSION['argent'] +=  $_SESSION['choix']['argent'];
    $_SESSION['satisfaction'] +=  $_SESSION['choix']['satisfaction'];
  }
  else {
    $_SESSION['argent'] +=  $_SESSION['choix']['argentNON'];
    $_SESSION['satisfaction'] +=  $_SESSION['choix']['satisfactionNON'];
  }

  $_SESSION['suivant'] = true;
  unset($_SESSION['choix']);
  header('Location: ../pageDeLancement/lancement.php');
  exit();
