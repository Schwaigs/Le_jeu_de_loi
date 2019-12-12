<?php
  session_start();
  require_once '../accesBDD/bddT3.php';

  //   Créez un objet PDO en utilisant les informations contenues dans bdd.php
  try {
    $pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));
  }
  catch( PDOException $e ) {
    echo 'Erreur : '.$e->getMessage();
    exit;
  }
  //  Construisez et exécute une requête préparée
  $result = $pdo->prepare(
      "SELECT * FROM loisDe". $_SESSION['login'] .""
  );

  // 4. On exécute la requête $result
  $ok2 = $result->execute();

  if (0 == $result->rowCount())
  {
    echo 'nope';
  }
  /*Afficher la liste des lois que l'utilistateur peut choisir */
  foreach ( $result as $row ) {
      echo $row['label'] .' :  '. $row['description'] . '<br>';
  }
