<?php
  //Démarrer la session
  session_start();

  if (!isset($_POST['login']) || empty(htmlspecialchars($_POST['login']))){
      header('Location: login.php');
      exit();
  }
  require_once '../accesBDD/initBase.php';
  $succes = initBase();
  $_SESSION['login'] = htmlspecialchars($_POST['login']);
  header('Location: lancement.php');
  exit();
  ?>
