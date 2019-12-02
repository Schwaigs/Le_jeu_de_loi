<?php
  //Démarrer la session
  session_start();


  if (isset($_POST['lois'])){
    $_SESSION['decision'] = 'lois';
  }
  else {
    $_SESSION['decision'] = 'events';
  }

  header('Location: ../pageDeLancement/lancement.php');
  exit();

 ?>
