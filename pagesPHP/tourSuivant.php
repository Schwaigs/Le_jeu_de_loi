z<?php
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
  if ($_SESSION['choixAFaire']){
    if ($_POST['choix'] == 'oui'){
      $_SESSION[$_SESSION['row']['ordreConcerneOui']] = $_SESSION[$_SESSION['row']['ordreConcerneOui']]  + ($_SESSION['row']['actionOui']);
    }
    else if ($_POST['choix'] == 'non'){
      $_SESSION[$_SESSION['row']['ordreConcerneNon']] = $_SESSION[$_SESSION['row']['ordreConcerneNon']]  + ($_SESSION['row']['actionNon']);
      if ($_SESSION[$_SESSION['row']['ordreConcerneNon']] > 100) {
        $_SESSION[$_SESSION['row']['ordreConcerneNon']] = 100;
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


  //Retour au formulaire de choix entre events ou lois
  unset($_SESSION['row']);
  unset($_SESSION['decision']);
  unset($_SESSION['numEvent']);

  //Les personnages vieilissent car les années passent
  $_SESSION['annee'] = $_SESSION['annee'] + 3;
  require_once '../accesBDD/classesPHP/Personnage.php';
  $perso = new Personnage();
  $perso->vieillirPerso();

  //Une année sur deux, il y a des mort ou une naissance
  if($_SESSION['mortNaissance'] == 'mort'){
    $perso->mortPerso();
    $_SESSION['mortNaissance'] = 'naissance';
  }
  else {
    $perso->creerPersonnage();
    $_SESSION['mortNaissance'] = 'mort';
  }

  //On met a jour l'arbre
  $heritage = new Heritage();
  try{
  //cherche les heritiers possibles apres la mise en place de la loi
      $heritage->majArbreHeritiers();
  }
  catch( PDOException $e ) {
    echo 'Erreur : '.$e->getMessage();
    exit;
  }

  //On peut passer à la suite
  $_SESSION['section'] ++;

  //on test les differents parametres qui font gagner ou perdre le joueur
  if ($_SESSION['annee'] >= 1789){
    $_SESSION['jeu'] = 'gagne';
    $_SESSION['messageFin'] = "Vous avez réussi à garder votre lignée sur le trône jusqu'à l'inévitable révolution française. Félicitation, vous avez gagner !";
  }

  if($_SESSION['noblesse'] == 0 || $_SESSION['clerge'] == 0 || $_SESSION['tiersEtat'] == 0 ){
     $_SESSION['jeu'] = 'perdu';
     $_SESSION['messageFin'] = "L'un des 3 ordres n'étant pas du tout satisfait de votre gestion du royaume, celui-ci a monter un coup d'état à l'encontre de votre famille. Vous avez perdu.";
  }
  //Si le joueur a perdu ou gagner on arrête le jeu ici et on lui affiche un écran de fin
  if( $_SESSION['jeu'] != 'en cours'){
    header('Location: fin.php');
    exit();
  }

  header('Location: ../pageDeLancement/lancement.php');
  exit();
