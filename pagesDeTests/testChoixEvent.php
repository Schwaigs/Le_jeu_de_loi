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

  echo $_POST['Lois'] . '<br>';

  $indexTab = strpos($_POST['Lois'],' ');

  $param = substr($_POST['Lois'],0, $indexTab);
  $label = substr($_POST['Lois'],$indexTab+1);
  echo 'param : ' . $param . '<br>';
  echo 'label : "' . $label . '"<br>';


  $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=1 WHERE label = :label");
  $description = $result->bindValue(':label',$label, PDO::PARAM_STR);
  $result->execute();
  if ($result->rowCount() == 0){
    header('Location: testLancement.php');
    exit();
  }
  echo 'nb ligne : ' . $result->rowCount()  . '<br>';
  $result = MyPDO::pdo()->prepare("UPDATE lois SET misEnPlace=-1 WHERE parametre = :parametre AND label != :label");
  $description = $result->bindValue(':parametre',$param, PDO::PARAM_STR);
  $description &= $result->bindValue(':label',$label, PDO::PARAM_STR);
  $result->execute();
  if ($result->rowCount() == 0){
    header('Location: testLancement.php');
    exit();
  }
  echo 'nb ligne : ' . $result->rowCount()  . '<br>';
  header('Location: testAfficheLois.php');
