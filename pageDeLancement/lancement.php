<?php

/*
* \file Lancement
* \par Permet de gèrer la recherche des héritiers.
 */

  //Démarrer la session
  session_start();

  require_once '../accesBDD/initBase.php';

  if (!isset($_SESSION['suivant'])){
      /*
      * \var suivant est une variable de session qui permet de savoir si le joueur a déjà choisit entre un événement ou un vote.
      */
      $_SESSION['suivant'] = true;
  }

  if (!isset($_SESSION['texteEvent'])){
      /*
      * \var texteEvent est une variable de session qui permet d'afficher le texte liée à un événement.
      */
      $_SESSION['texteEvent'] = "Bienvenue";
  }

  if (!isset($_SESSION['annee'])){
      /*
      * \var année est une variable de session qui contient l'année courante dans le jeu.
      */
      $_SESSION['annee'] = 1700;
  }

  if (!isset($_SESSION['idCarac'])){
    /*
    * \var idCarac est une variable de session qui contient l'id du personnage dont on doit afficher les caractéristiques.
    */
    $_SESSION['idCarac'] = -1;
  }

  //Récupération de l'id en variable de session
  if (isset($_GET['id'])){
    $_SESSION['idCarac'] = $_GET['id'];
  }

  if (!isset($_SESSION['jeu'])){
    /*
    * \var jeu est une variable de session qui permet de savoir si le joueur à perdu, gagné ou bien joue encore.
    */
    $_SESSION['jeu'] = 'en cours';
  }

  if (!isset($_SESSION['messageFin'])){
    /*
    * \var messageFin est une variable de session qui contient le message à afficher quand le joueur perd ou gagne.
    */
    $_SESSION['messageFin'] = '';
  }

  if (!isset($_SESSION['message'])){
    /*
    * \var message est une variable de session qui permet d'afficher au joueurs différentes informations au cours de la partie.
    */
    $_SESSION['message'] = "";
  }

  if (!isset($_SESSION['noblesse'])){
    /*
    * \var noblesse est une variable de session qui évalue la relation entre le joueur et la noblesse.
    */
    $_SESSION['noblesse'] = 50;
  }

  if (!isset($_SESSION['clerge'])){
    /*
    * \var clerge est une variable de session qui évalue la relation entre le joueur et le clergé.
    */
    $_SESSION['clerge'] = 50;
  }

  if (!isset($_SESSION['tiersEtat'])){
    /*
    * \var tiersEtat est une variable de session qui évalue la relation entre le joueur et le tiers-état.
    */
    $_SESSION['tiersEtat'] = 50;
  }

  if (!isset($_SESSION['delaisMort'])){
    /*
    * \var suivant est une variable de session qui permet de savoir si le joueur a déjà choisit entre un événement ou un vote.
    */
    $_SESSION['delaisMort'] = 3;
  }

  if (!isset($_SESSION['delaisMortInit'])){
    /*
    * \var suivant est une variable de session qui permet de savoir si le joueur a déjà choisit entre un événement ou un vote.
    */
    $_SESSION['delaisMortInit'] = true;
  }

  //LE joeur à un délai avant de perdre la partie si un ordre n'est pas satisfait
  if ((($_SESSION['tiersEtat'] == 0) || ($_SESSION['clerge'] == 0) || ($_SESSION['noblesse'] == 0)) && (!$_SESSION['delaisMortInit'])) {
    $_SESSION['delaisMort'] --;
    $_SESSION['delaisMortInit'] = true;
    if ($_SESSION['delaisMort'] == 0) {
      $_SESSION['jeu'] = 'perdu';
      $_SESSION['messageFin'] = "L'un des 3 ordres n'étant pas du tout satisfait de votre gestion du royaume, celui-ci a monter un coup d'état à l'encontre de votre famille. Vous avez perdu.";
      header('Location: ../pagesPHP/fin.php');
      exit();
    }
  }
  else if (!$_SESSION['delaisMortInit']) {
    $_SESSION['delaisMort'] = 3;
  }

  if (!isset($_SESSION['action'])) {
    $_SESSION['action'] = 'lois';
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
    <!-- Permet d'utiliser une police d'écriture au style moyenâgeux -->
    <link href="https://fonts.googleapis.com/css?family=Almendra&display=swap" rel="stylesheet">
  </head>

  <body>

    <header>
      <div class="flex-container">
        <div id="annee" style="flex-grow: 2"> <h1><?php echo $_SESSION['annee'] ?></h1> </div>
        <!-- Si on clique sur le titre on recommence une nouvelle partie -->
        <div id="titreJeu" style="flex-grow: 6"><a href="../pagesPHP/quitter.php">Jeu de lois</a></div>
        <!-- Si on clique sur le livre on arrive sur la page d'encyclopédie qui éxplique certains termes -->
        <div id="encyclo" style="flex-grow: 2">
          <a href="../pagesPHP/encyclopedie.php" onclick="window.open(this.href); return false;"><img id="imgEncyclo" src="../images/encyclopedie.png"></a>
        </div>
      </div>
    </header>

    <main>
      <div class="main">
        <!-- Zone de l'arbre généalogique -->
        <div id="bandeauArbre">
          <h1>Arbre généalogique</h1>
          <!-- Création du bandeau dépliable  -->
          <div id="arbreDepl" class="overlayArbre">

           <!-- Bouton pour fermer/replier le bandeau -->
           <a href="javascript:void(0)" class="btnFermer" onclick="fermeArbre()">&times;</a>

           <!-- Contenu de l'overlay -->
           <div class="tree">
             <!-- Remplissage par la page de l'abre généalogique créé à part -->
             <?php
              // include '../pagesPHP/arbreGenealogique.php';
                 if (!isset($_GET['refresh'])){
                  /*
                  * \var refresh est une variable de session qui permet de rafraichir la page lors de chaque action.
                  */
                  header('Location: lancement.php?refresh=0');
                  exit();
                }
             ?>
           </div>
          </div>

          <!-- Bouton pour ouvrir/déplier le bandeau -->
          <br>
          <span onclick="ouvreArbre()">
	           <div id="imgBoutonArbre">  </div>
	        </span>
	          <!-- Lorsque la souris passe au dessus de l'image, elle change-->
          <script src="../js/index.js"></script>
        </div>
        <!-- Zone principale de jeu -->
        <div class="container" id="event">

          <div class="jauges">
            <div class="columnDiffJauges" id="jaugeUne">
              <!-- Valeur brute qui servira au debuggage (sera affiché la valeur de la varaible)-->
	            <p> Noblesse : <?php echo $_SESSION['noblesse']?> / 100</p>
              <!-- Le height gère la hauteur globale de la jauge -->
              <div class="bar-container" style="height: 10rem;">
                <div class="goal-bar">
                  <div class="bar-wrap">
                    <!-- On gère la taille de la jauge via le translateY-->
                    <div class="bar" style="transform: translateY(<?php echo 100-$_SESSION['noblesse'] ?>%);">
                    </div>
                  </div>
                </div> <!-- /.goal-bar -->
              </div>
            </div>

            <div class="columnDiffJauges" id="jaugeDeux">
              <!-- Valeur brute qui servira au debuggage (sera affiché la valeur de la varaible)-->
              <p> Clergé : <?php echo $_SESSION['clerge']?> / 100</p>
              <!-- Le height gère la hauteur globale de la jauge -->
              <div class="bar-container" style="height: 10rem;">
                <div class="goal-bar">
                  <div class="bar-wrap">
                    <!-- On gère la taille de la jauge via le translateY-->
                    <div class="bar" style="transform: translateY(<?php echo 100-$_SESSION['clerge'] ?>%);">
                    </div>
                  </div>
                </div> <!-- /.goal-bar -->
              </div>
            </div>

            <div class="columnDiffJauges" id="jaugeTrois">
              <!-- Valeur brute qui servira au debuggage (sera affiché la valeur de la varaible)-->
	            <p> Tiers état : <?php echo $_SESSION['tiersEtat']?> / 100</p>
              <!-- Le height gère la hauteur globale de la jauge -->
              <div class="bar-container" style="height: 10rem;">
                <div class="goal-bar">
                  <div class="bar-wrap">
                    <!-- On gère la taille de la jauge via le translateY-->
                    <div class="bar" style="transform: translateY(<?php echo 100-$_SESSION['tiersEtat'] ?>%);">
                    </div>
                  </div>
                </div> <!-- /.goal-bar -->
              </div>
            </div>
          </div>

          <div class="evenements">
            <div class="flex-event-header">
              <div style="flex-grow: 10">
                <h1>Les aléas de la vie</h1>
              </div>

            </div>
            <div class="contenuEvent">
              <!-- Remplissage par la page des event et de vote créé à part -->
                <?php
                if ($_SESSION['delaisMort'] < 3) {
                  echo 'délai mort : ' .$_SESSION['delaisMort'];
                }
                include '../pagesPHP/choixEventLoi.php';
                ?>
            </div>
          </div>
        </div>

        <div class="column">
          <!-- Zone des lois en place -->
          <div id="lois">
            <h2>Lois promulguées:</h2>
              <!-- Remplissage par la page d'affichage des lois créé à part -->
              <?php
              include '../pagesPHP/afficheLois.php';
              ?>
          </div>
          <!-- Zone des caractéristiques des personnages -->
          <div id="carac">
            <div id="affichageImageEtTexte">
                <!-- Remplissage par la page d'affichage des caractéristiques créé à part -->
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
