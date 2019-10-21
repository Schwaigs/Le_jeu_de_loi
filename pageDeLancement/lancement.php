<?php
  //Démarrer la session
  session_start();

  if (!isset($_SESSION['login']) || empty($_SESSION['login'])){
      header('Location: login.php');
      exit();
  }

  if (!isset($_SESSION['numEvent'])){
      $_SESSION['numEvent'] = 1;
  }

  if (!isset($_SESSION['annee'])){
      $_SESSION['annee'] = 1763;
  }

  require_once '../accesBDD/classesPHP/Arbre.php';

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
        <div style="flex-grow: 1"> <h1><?php echo $_SESSION['annee'] ?></h1> </div>
        <div id="titreJeu" style="flex-grow: 8">Jeu de lois</div>
        <div id="encyclo" style="flex-grow: 1">
          <a href="../pagesPHP/encyclopedie.php" onclick="window.open(this.href); return false;">Encyclopédie</a>
        </div>
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
             <?php
                 include '../pagesPHP/arbreGenealogique.php';
             ?>
           </div>
          </div>

          <!-- Bouton pour ouvrir/déplier le bandeau -->
          <br>
          <span onclick="ouvreArbre()">Afficher >></span>
          <script src="../js/index.js"></script>
        </div>
        <div class="container" id="event">
          <h1>Evénements</h1>
          <div class="contenuEvent">
          <?php
          include '../pagesPHP/evenements.php'
          ?>
          </div>
        </div>
        <div class="column">
          <div id="lois">
            <h2>Liste des lois en vigueur:</h2> <br><br>
            <?php
            include '../pagesPHP/afficheLois.php'
            ?>
          </div>
          <div id="carac">
            <h2>Caractéristiques du personnage:</h2>
            <div>
              <?php
                  include '../pagesPHP/infoCarac.php';
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>

  </body>


</html>
