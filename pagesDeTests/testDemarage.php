<?php
  //Démarrer la session
  session_start();

  if (!isset($_POST['login']) || empty(htmlspecialchars($_POST['login']))){
      header('Location: testLogin.php');
      exit();
  }
  $_SESSION['login'] = htmlspecialchars($_POST['login']);
  header('Location: testLancement.php');
  exit();
  ?>
