<?php
  //Démarrer la session
  session_start();

  //Redirection si pas méthode POST
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
  {
      header('Location: testBase.php');
      exit();
  }

  if (!isset($_SESSION['choix']) || !isset($_POST['choix'])){
      header('Location: testBase.php');
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

  echo "argent : " . $_SESSION['argent'] . '<br>';
  echo "satisfaction : " . $_SESSION['satisfaction'] . '<br>';

  unset($_SESSION['choix']);
  header('Location: testBase.php');
  exit();
