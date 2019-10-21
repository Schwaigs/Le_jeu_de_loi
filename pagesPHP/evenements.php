<!--Afficher les différents choix de lois possible !-->
<form action="../pagesPHP/choixEvent.php" method="POST" name="formEvent">
  Quelles lois voulez-vous mettre en place ? <br><br>
  <?php
  require_once '../accesBDD/bddT3.php';

  //   Créez un objet PDO en utilisant les informations contenues dans bdd.php
  try {
    $pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
  }
  catch( PDOException $e ) {
    echo 'Erreur : '.$e->getMessage();
    exit;
  }
  //  Construisez et exécute une requête préparée
  $result = $pdo->prepare(
      "SELECT * FROM lois WHERE misEnPlace=0"
  );
  // 2. Assigne 10 au 1er paramètre
  // $ok1 = $result->bindValue(
  //     ':table', "lois", PDO::PARAM_STR
  // );

  // 4. On exécute la requête $result
  $ok2 = $result->execute();


  // si la requête s'est bien passée, il supprime la session et demande une redirection vers signin.php.
  // sinon il demande une redirection vers welcome.php.

  if (0 == $result->rowCount())
  {
    echo 'nope';
  }
    /*Afficher la liste des lois que l'utilistateur peut choisir */
    foreach ( $result as $row ) {
        echo '<input type="radio" name="Lois" value="' . $row['label'] .'">'. $row['label'] . '<br>';
    }
    ?>
  <input type="submit" value="Voter">
</form>
