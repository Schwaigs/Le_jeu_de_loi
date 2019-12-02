<?php
  //Démarrer la session
  session_start();
  require_once '../accesBDD/initBase.php';

  //Mise en place de la section, on débute à 1 et on va jusqu'à 5 sections (=1 cycle)
  //Puis on recommence
  if (!isset($_SESSION['section'])){
      $_SESSION['section'] = 1;
  }

  //Si on passe la 5ème section, on recommence un nouveau cycle de 5 sections
  if ($_SESSION['section'] > 5){
    $_SESSION['section'] = 1;
  }

  //On initialise le nombre de lois que l'on peut voter à 2 par cycle de 5 sections,
  //si on est au début d'une section on a le droit à 2 votes
  if (!isset($_SESSION['nbLois']) || $_SESSION['section'] == 1){
      $_SESSION['nbLois'] = 2;
  }

  if (!isset($_SESSION['suivant'])){
      $_SESSION['suivant'] = true;
  }

  if (!isset($_SESSION['texteEvent'])){
      $_SESSION['texteEvent'] = "Bienvenue";
  }

  if (!isset($_SESSION['annee'])){
      $_SESSION['annee'] = 1763;
  }

  if (!isset($_SESSION['idCarac'])){
    $_SESSION['idCarac'] = -1;
  }

  //Récupération de l'id en variable de session
  if (isset($_GET['id'])){
    $_SESSION['idCarac'] = $_GET['id'];
  }

  if (!isset($_SESSION['jeu'])){
    $_SESSION['jeu'] = 'enCours';
  }

  if (!isset($_SESSION['message'])){
    $_SESSION['message'] = "";
  }

  if (!isset($_SESSION['noblesse'])){
    $_SESSION['noblesse'] = 100;
  }

  if (!isset($_SESSION['clerge'])){
    $_SESSION['clerge'] = 100;
  }

  if (!isset($_SESSION['tiersEtat'])){
    $_SESSION['tiersEtat'] = 100;
  }

  if (!isset($_SESSION['peutEnfant'])){
    $_SESSION['peutEnfant'] = 1; //si le roi actuel peut avoir des enfant (1) ou non (0)
  }

  if (!isset($_SESSION['satisfaction'])){
    $_SESSION['satisfaction'] = 100;
  }

  require_once '../accesBDD/classesPHP/Arbre.php';
  require_once '../accesBDD/chercheCaracPerso.php';

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
    <link href="https://fonts.googleapis.com/css?family=Almendra&display=swap" rel="stylesheet">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div id="annee" style="flex-grow: 1"> <h1><?php echo $_SESSION['annee'] ?></h1> </div>
        <div id="titreJeu" style="flex-grow: 8"><a href="../pagesPHP/quitter.php">Jeu de lois</div>
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
          <div id="arbreDepl" class="overlayArbre">

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
          <div class="flex-event-header">
            <div style="flex-grow: 7">
              <h1>Les aléas de la vie</h1>
            </div>
          </div>
          <div class="contenuEvent">
          <?php
          include '../pagesPHP/choixEventLoi.php';
          ?>
          </div>
        </div>
        <div class="column">
          <div id="lois">
            <h2>Lois promulguées:</h2>
            <?php
            include '../pagesPHP/afficheLois.php'
            ?>
          </div>
          <div id="carac">
            <h2>Registre royal:</h2>
            <div id="affichageImageEtTexte">

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
