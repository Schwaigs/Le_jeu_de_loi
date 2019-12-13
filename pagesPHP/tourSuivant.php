<?php
require_once '../accesBDD/classesPHP/Heritage.php';

  //Démarrer la session
  session_start();

  //Redirection si pas méthode POST
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
  {
      header('Location: ../pageDeLancement/lancement.php');
      exit();
  }

  //Si l'évènement correspond à un choix
  if (isset($_SESSION['choixAFaire']))
  {
    if ($_SESSION['choixAFaire']){
      if ($_POST['choix'] == 'oui'){
        $_SESSION[$_SESSION['row']['ordreConcerneOui']] = $_SESSION[$_SESSION['row']['ordreConcerneOui']]  + ($_SESSION['row']['actionOui']);
      }
      else if ($_POST['choix'] == 'non'){
        $_SESSION[$_SESSION['row']['ordreConcerneNon']] = $_SESSION[$_SESSION['row']['ordreConcerneNon']]  + ($_SESSION['row']['actionNon']);
        if ($_SESSION[$_SESSION['row']['ordreConcerneNon']] > 100) {
          $_SESSION[$_SESSION['row']['ordreConcerneNon']] = 100;
        }
        else if ($_SESSION[$_SESSION['row']['ordreConcerneNon']] < 0) {
          $_SESSION[$_SESSION['row']['ordreConcerneNon']] = 0;
        }
      }
      else {
        header('Location: ../pageDeLancement/lancement.php');
        exit();
      }
    }
    else {
      //Si le joueur n'a pas fait de choix
      $_SESSION[$_SESSION['row']['ordreConcerneOui']] = $_SESSION[$_SESSION['row']['ordreConcerneOui']]  + ($_SESSION['row']['actionOui']);
    }

    if ($_SESSION[$_SESSION['row']['ordreConcerneOui']] > 100) {
      $_SESSION[$_SESSION['row']['ordreConcerneOui']] = 100;
    }
    else if ($_SESSION[$_SESSION['row']['ordreConcerneOui']] < 0) {
      $_SESSION[$_SESSION['row']['ordreConcerneOui']] = 0;
    }

  }
  else {
    //Si c'est un évènement suite à une loi
    $nouveauScoreOrdre1 = $_SESSION[$_SESSION['choix']['ordreConcerneOui']] + ($_SESSION['choix']['actionOui']);
    if($nouveauScoreOrdre1 > 100){
      $nouveauScoreOrdre1 = 100;
    }
    else if($nouveauScoreOrdre1 < 0){
        $nouveauScoreOrdre1= 0;
    }
    $_SESSION[$_SESSION['choix']['ordreConcerneOui']] = $nouveauScoreOrdre1;
    
    $nouveauScoreOrdre2 = $_SESSION[$_SESSION['choix']['ordreConcerneNon']] + ($_SESSION['choix']['actionNon']);
    if($nouveauScoreOrdre2 > 100){
      $nouveauScoreOrdre2 = 100;
    }
    else if($nouveauScoreOrdre2 < 0){
        $nouveauScoreOrdre2 = 0;
    }
    $_SESSION[$_SESSION['choix']['ordreConcerneNon']] = $nouveauScoreOrdre2;
  }

   //Remettre le compteur du délai avant de perdre
   $_SESSION['delaisMortInit'] = false;

  //Retour au formulaire de choix entre events ou lois
  unset($_SESSION['row']);
  unset($_SESSION['decision']);
  unset($_SESSION['numEvent']);

  //Les personnages vieilissent car les années passent
  $_SESSION['annee'] = $_SESSION['annee'] + 5;
  require_once '../accesBDD/classesPHP/Personnage.php';
  $perso = new Personnage();
  $perso->vieillirPerso();

  //Chaque tour de jeu il y a des morts et une à 5 naissance(s)
  $nbNaissance = rand(1,6);
  for ($i=0; $i < $nbNaissance; $i++) {
    $perso->creerPersonnage();
  }
  $perso->mortPerso();
  
  //On peut passer à la suite
  $_SESSION['action'] = 'lois';

  //on test les differents parametres qui font gagner ou perdre le joueur
  if ($_SESSION['annee'] >= 1789){
    $_SESSION['jeu'] = 'gagne';
    $_SESSION['messageFin'] = "Vous avez réussi à garder votre lignée sur le trône jusqu'à l'inévitable révolution française. Félicitation, vous avez gagner !";
    header('Location: fin.php');
    exit();
  }

  //Le roi meurt à la fin de son règne et on cherche le nouvel héritier
  include '../pagesDeTests/testHeritage.php';

  header('Location: ../pageDeLancement/lancement.php');
  exit();
