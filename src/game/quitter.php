<?php
  //Démarrer la session pour pouvoir lancer d'autres instructions qui l'utilisent
  session_start();

  require_once '../model/MyPDO.php';

  try{
    $resultLois = MyPDO::pdo()->prepare("
    DROP TABLE `loisDe" . $_SESSION['login'] ."`");

    $execution = $resultLois->execute();

    echo 'exec create : ' . var_dump($execution) . '<br>';

  }
  catch( Exception $e ) {
      echo 'Erreur : '.$e->getMessage();
      exit;
  }

  echo '<br>------------------------<br>';

  try{
    $resultPerso = MyPDO::pdo()->prepare("
    DROP TABLE `persoDe" . $_SESSION['login'] ."`");

    $execution20 = $resultPerso->execute();

    echo 'exec create : ' . var_dump($execution20) . '<br>';

  }
  catch( Exception $e ) {
      echo 'Erreur : '.$e->getMessage();
      exit;
  }

  //supprime la session du joueur
  session_destroy();
  //renvoie sur la page d'acceuil du site
  header('Location: ../home/index.php');
  exit();
  ?>
