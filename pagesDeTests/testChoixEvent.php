<?php
  //Démarrer la session
  session_start();

  //Redirection si pas méthode POST
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
  {
      header('Location: testLancement.php');
      exit();
  }

  if (!isset($_POST['Lois'])){
      header('Location: testLancement.php');
      exit();
  }

  require_once '../accesBDD/bddT3.php';
  require_once '../accesBDD/MyPDO.php';

  try{
    $pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
  }
  catch( PDOException $e ) {
      echo 'Erreur : '.$e->getMessage();
      exit;
  }
  $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=1 WHERE paramVal = :parametre");
  $description = $result->bindValue(':parametre',htmlspecialchars($_POST['Lois']), PDO::PARAM_STR);
  $result->execute();
  if ($result->rowCount() == 0){
    header('Location: testLancement.php');
    exit();
  }
  header('Location: testAfficheLois.php');
