<?php
session_start();
require_once '../accesBDD/classesPHP/Personnage.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Page test personnage</title>
    </head>
    <body>

        <?php
            $personnage = new Personnage();
            try{
                echo 'nb de lignes modifies = '.$personnage->creerPersonnage().'<br>';
            }
            catch( PDOException $e ) {
                echo 'Erreur : '.$e->getMessage();
                exit;
            }
        ?>

        <a href="arbreGenealogique.php"> Cliquez pour aller voir l'arbre </a>
    </body>
</html>
