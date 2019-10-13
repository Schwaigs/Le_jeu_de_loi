<?php
require_once './Heritage.php';
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
            if($idNouveauRoi == 0){
                echo "vous n'avez aucun héritiers, vous avez perdu";
            }
            else{
                echo 'id du nouveau roi = '.$idNouveauRoi;
            }
        }
        catch( PDOException $e ) {
            echo 'Erreur : '.$e->getMessage();
            exit;
        }
    ?>

    </body>
</html>