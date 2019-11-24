<?php
  //Démarrer la session
  session_start();
  session_destroy();
  header('Location: ../pageDeLancement/login.php');
  exit();
  ?>
