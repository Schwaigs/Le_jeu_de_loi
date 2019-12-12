<?php
session_start();
require_once '../accesBDD/classesPHP/Heritage.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="arbreGenealogique.css">
        <title>Page test heritage</title>
    </head>
    <body>

    <?php
        $heritage = new Heritage();
        try{
            $idNouveauRoi = $heritage->choisiRoi();
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    ?>

    </body>
</html>
