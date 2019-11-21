<?php
  //Démarrer la session
  session_start();
  require_once '../accesBDD/initBase.php';

  if (!isset($_SESSION['login']) || empty($_SESSION['login'])){
      $succes = initBase();
      header('Location: login.php');
      exit();
  }

  if (!isset($_SESSION['numEvent'])){
      $_SESSION['numEvent'] = 1;
  }

  if (!isset($_SESSION['annee'])){
      $_SESSION['annee'] = 1763;
  }

  if (!isset($_SESSION['argent'])){
    $_SESSION['argent'] = 100;
  }

  if (!isset($_SESSION['jeu'])){
    $_SESSION['jeu'] = 'enCours';
  }

  if (!isset($_SESSION['peutEnfant'])){
    $_SESSION['peutEnfant'] = 1; //si le roi actuel peut avoir des enfant (1) ou non (0)
  }

  if (!isset($_SESSION['satisfaction'])){
    $_SESSION['satisfaction'] = 100;
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
    <link href="https://fonts.googleapis.com/css?family=Almendra&display=swap" rel="stylesheet">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div id="annee" style="flex-grow: 1"> <h1><?php echo $_SESSION['annee'] ?></h1> </div>
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
            <div style="flex-grow: 3" class="overlayBandeau">
              <span onclick="ouvreBandeauLoi()"> <h2>Décret royal</h2> </span>
              <script src="../js/ajoutRetireLoi.js"></script>

              <div id="bandeauLoiDepl" class="overlayBandeau-content" style="height: 0%;">
                <?php
                    include '../pagesPHP/ajoutRetireLoi.php';
                ?>
               <!-- Bouton pour fermer/replier le bandeau -->
  <!--         <a href="javascript:void(0)" class="btnFermer" onclick="fermeArbre()">&times;</a>     -->

               <!-- Contenu de l'overlay -->



              </div>

            </div>
          </div>
          <div class="contenuEvent">
          <?php
          include '../pagesPHP/evenements.php'
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
              <img id="imgPerso" src="../images/maenele.jpg">
              <div id="affichageTexte">
                <?php
                    include '../pagesPHP/infoCarac.php';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

  </body>
</html>
