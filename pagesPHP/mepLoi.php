<?php
    session_start();
    $_SESSION['message'] = "";
    // si la méthode HTTP utilisée n'est pas POST on demande une redirection vers le fichier lancement.php
    if ($_SERVER['REQUEST_METHOD']!='POST'){
        header('Location: ../pageDeLancement/lancement.php');
        exit();
    }
    // Si aucune loi n'as été choisit par le joueur on demande une redirection vers le fichier lancement.php pour qu'il en choissise une
    if (!isset($_POST['Lois']) || empty($_POST['Lois'])){
        header('Location: ../pageDeLancement/lancement.php');
        exit();
    }

    require_once '../accesBDD/classesPHP/Loi.php';

    //On a concaténer les 3 informations nécéssaires sur la loi en une chaine de caractères séparé par des espaces
    //On sépare donc ses 3 informations
    $value = explode(' ',$_POST['Lois']);
    $ajoutSupp = $value[0];
    $parametre = $value[1];
    $paramVal = $value[2];
    //echo'ajoutSupp = '.$ajoutSupp.' parametre = '.$parametre.'  paramVal = '.$paramVal;

    $loi = new Loi($parametre,$paramVal);

    //Si il s'agit d'une nouvelle loi à mettre en place on appel ajoutLoi()
    if($ajoutSupp == 'ajout'){
        try{
            echo 'nb de lignes modifies = '.$loi->ajoutLoi().'<br>';
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    }
    //Si il s'agit d'une loi existante à abroger on appel suppLoi()
    else{
        try{
            echo 'nb de lignes modifies = '.$loi->suppLoi().'<br>';
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    }

    //Puis on passe à la section suvante, 3 ans plus tard
    $_SESSION['annee'] = $_SESSION['annee'] +3;
    $_SESSION['section'] ++;
    //On vide la variable décision pour pouvoir afficher au joueur le choix entre évènement et loi
    unset($_SESSION['decision']);
    header('Location: ../pageDeLancement/lancement.php');
    exit();
