<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Titre non défini </title>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div style="flex-grow: 1">Année</div>
        <div style="flex-grow: 8">Titre</div>
        <div style="flex-grow: 1">Aide/Encyclopédie</div>
      </div>
    </header>

    <main>
      <div class="main">
        <div class="container" id="event">
          <p>Première phrase</p>
          <?php
            /*Afficher la description de l'évènement */


            ?>

            <!--Afficher les différents choix de lois possible !-->
            <form action="testChoixEvent.php" method="POST" name="formEvent">
              Quelles lois voulez-vous mettre en place ? <br><br>
              <?php
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
        </div>
      </div>
    </main>
  </body>
</html>
