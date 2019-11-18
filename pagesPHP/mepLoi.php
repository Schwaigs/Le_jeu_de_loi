<?php
    session_start();
    $_SESSION['message'];
    // 1) demande une redirection vers le fichier ajoutRetireLoi.php 
    // si la méthode HTTP utilisée n'est pas POST
    if ($_SERVER['REQUEST_METHOD']!='POST'){
        header('Location: ajoutRetireLoi.php');
        exit();
    }
    if (!isset($_POST['Lois']) || empty($_POST['Lois'])){
        header('Location: ajoutRetireLoi.php');
        exit();
    }
    require_once '../accesBDD/classesPHP/Loi.php';

    $value = explode(' ',$_POST['Lois']);
    $ajoutSupp = $value[0];
    $parametre = $value[1];
    $paramVal = $value[2];
    echo'ajoutSupp = '.$ajoutSupp.' parametre = '.$parametre.'  paramVal = '.$paramVal;

    $loi = new Loi($parametre,$paramVal);

    if($ajoutSupp == 'ajout'){
        try{
            echo 'nb de lignes modifies = '.$loi->ajoutLoi().'<br>';
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    }
    else{
        try{
            echo 'nb de lignes modifies = '.$loi->suppLoi().'<br>';
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    }

    header('Location: ajoutRetireLoi.php');
    exit();


