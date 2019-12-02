<?php
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
            $heritage->majHeritiersLois();
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    ?>

    </body>
</html>