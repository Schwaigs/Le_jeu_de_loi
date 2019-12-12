<?php
  //Démarrer la session
  session_start();

  if (isset($_POST['demarage'])){
    //Initialisation de la base
    require_once '../accesBDD/initBase.php';
    $succes = initBase();

    //Numéro associé à un utilistateur
    $_SESSION['login'] = rand(1,100000);
    header('Location: lancement.php');
    exit();
  }
  elseif (isset($_POST['tutoriel'])){
    header('Location: tuto.php');
    exit();
  }
  elseif (isset($_POST['aide'])){
    header('Location: aide.php');
    exit();
  }

  //Si rien n'est bon, c'est une erreur et on retourne sur l'écran d'acceuil
  header('Location: acceuil.php');
  exit();

  ?>
