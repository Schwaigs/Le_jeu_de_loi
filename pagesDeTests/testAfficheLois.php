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
        <div class="container" id="arbre">
          <p>Première phrase</p>
        </div>
        <div class="container" id="event">
        </div>
        <div class="column">
          <div id="lois">
            <p>Ceci est une loi</p>
            Liste des lois en place : <br><br>
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
                "SELECT * FROM lois WHERE misEnPlace=1"
            );

            // 4. On exécute la requête $result
            $ok2 = $result->execute();

            if (0 == $result->rowCount())
            {
              echo 'nope';
            }
            else {
              /*Afficher la liste des lois que l'utilistateur peut choisir */
              foreach ( $result as $row ) {
                  echo '<p>'. $row['description'] . '</p><br>';
              }
            }
              ?>
          </div>
          <div id="carac">
            <p>Ceci est une caractéristique</p>
          </div>
        </div>
      </div>
    </main>

  </body>


</html>
