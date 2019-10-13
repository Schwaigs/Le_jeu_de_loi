<?php
require_once './Arbre.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="arbreGenealogique.css">
        <title>Page arbre généalogique</title>
    </head>
    <body>

    <?php
        $arbre = new Arbre();
        try{
            $arbre->initArbre();
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    ?>

    </body>
</html>