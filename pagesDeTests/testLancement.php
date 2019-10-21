<?php
  //Démarrer la session
  session_start();

  if (!isset($_SESSION['login']) || empty($_SESSION['login'])){
      header('Location: testLogin.php');
      exit();
  }

  if (!isset($_SESSION['numEvent'])){
      $_SESSION['numEvent'] = 1;
  }


  ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8"
    name="viewport"
    content="width=device-width, initial-scale=1">
    <title> Jeu de Lois </title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/arbreGenealogique.css">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div style="flex-grow: 1">Année</div>
        <div id="titreJeu" style="flex-grow: 8">Jeu de lois</div>
        <div id="encyclo" style="flex-grow: 1">Aide/Encyclopédie</div>
      </div>
    </header>

    <main>
      <div class="main">
        <div id="bandeauArbre">
          <h1>Arbre généalogique</h1>
          <!-- Création du bandeau dépliable  -->
          <div id="arbreDepl" class="overlay">

           <!-- Bouton pour fermer/replier le bandeau -->
           <a href="javascript:void(0)" class="btnFermer" onclick="fermeArbre()">&times;</a>

           <!-- Contenu de l'overlay -->
           <div class="tree">
               <ul>
                   <li> <!-- arbre en entier est un element de la liste -->

                       <a href="#">Parent</a>

                       <ul> <!-- création d'une nouvelle liste -->

                           <li> <!-- chaque enfant et sa descendance est un élément -->
                               <a href="#">Child 1</a>
                               <ul>
                                   <li>
                                       <a href="#">petit enfant 1</a>
                                   </li>
                               </ul>
                           </li>


                           <li> <!-- chaque enfant et sa descendance est un élément -->
                               <a href="#">Child 2</a>
                               <ul>
                                   <li><a href="#">petit enfant 2</a></li>
                                   <li>
                                       <a href="#">petit enfant 3</a>
                                       <ul>
                                           <li>
                                               <a href="#">Great Grand Child</a>
                                           </li>
                                           <li>
                                               <a href="#">Great Grand Child</a>
                                           </li>
                                           <li>
                                               <a href="#">Great Grand Child</a>
                                           </li>
                                       </ul>
                                   </li>
                                   <li><a href="#">Grand Child</a></li>
                               </ul>
                           </li>
                       </ul>

                   </li> <!-- fin de l'arbre en entier-->
               </ul>
           </div>
          </div>

          <!-- Bouton pour ouvrir/déplier le bandeau -->
          <br>
          <span onclick="ouvreArbre()">Afficher >></span>
          <script src="../js/index.js"></script>
        </div>
        <div class="container" id="event">
          <h1>Evenements</h1>
          <div class="contenuEvent">
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
              "SELECT * FROM evenements WHERE id=:id"
          );

          $id = $result->bindValue(':id',htmlspecialchars($_SESSION['numEvent']), PDO::PARAM_INT);

          // 4. On exécute la requête $result
          $ok2 = $result->execute();


          if (0 == $result->rowCount())
          {
            echo 'Plus aucun évènement...';
          }
            /*Afficher la liste des lois que l'utilistateur peut choisir */
            foreach ( $result as $row ) {
              echo $row['texte'] . '<br>';
              $row['classe'];
              try {
                $row['fonction'];
              }
              catch( PDOException $e ) {
                  echo 'Erreur : '.$e->getMessage();
                  exit;
              }
              echo '<br>';
            }
            ?>

          <form action="testChoixEvent.php" method="POST" name="formEvent">
            Quelles lois voulez-vous mettre en place ? <br><br>
            <?php
            require_once '../accesBDD/MyPDO.php';
            require_once '../accesBDD/classesPHP/Personnage.php';
                $personnage = new Personnage();
                try{
                    $personnage->vieillirPerso();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }
            if ($_SESSION['numEvent'] == 3){
              echo 'Quelles lois voulez-vous mettre en place ? <br><br>';
              require_once '../accesBDD/classesPHP/CtrlLoi.php';
                  $ctrlLoi = new CtrlLoi();
                  try{
                      $ctrlLoi->afficheLoiPeutVoter();
                  }
                  catch( PDOException $e ) {
                      echo 'Erreur : '.$e->getMessage();
                      exit;
                  }

              ?>
            <br>
            <br>
            <input type="submit" value="Voter">
          </form>

          </div>
        </div>
        <div class="column">
          <div id="lois">
            <h2>Liste des lois en vigueur:</h2> <br><br>
            <?php
            require_once '../accesBDD/MyPDO.php';
            require_once '../accesBDD/classesPHP/CtrlLoi.php';
                $ctrlLoi = new CtrlLoi();
                try{
                    $ctrlLoi->afficheLoiEnPlace();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }

              ?>
          </div>
          <div id="carac">
            <h2>Caractéristiques du personnage:</h2>

          </div>
        </div>
      </div>
    </main>

  </body>


</html>
