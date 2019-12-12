<?php
session_start();
require_once '../accesBDD/classesPHP/Personnage.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="arbreGenealogique.css">
        <title>Page test heritage</title>
    </head>
    <body>

    <?php
        $perso = new Personnage();
        try{
            $nbMorts = $perso->mortPerso();
            echo ' '.$nbMorts.'<br>';
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    ?>

    </body>
</html>
