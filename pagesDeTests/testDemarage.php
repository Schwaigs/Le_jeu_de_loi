<?php
  //Démarrer la session
  session_start();

  if (!isset($_POST['login']) || empty(htmlspecialchars($_POST['login']))){
      header('Location: testLogin.php');
      exit();
  }
  $_SESSION['login'] = htmlspecialchars($_POST['login']);
  $_SESSION['numEvent'] = 1;
  require_once '../accesBDD/bddT3.php';
  require_once '../accesBDD/MyPDO.php';
  $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=0");
  $result->execute();
  header('Location: testLancement.php');
  exit();
  ?>
