<?php
session_start();
require_once '../accesBDD/classesPHP/Loi.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Page test loi</title>
    </head>
    <body>

        <?php
            $loi = new Loi('ordreNaissance','0');
            try{
                echo 'parametre= '.$loi->getParametre().'<br>';
                echo 'paramVal = '.$loi->getParamVal().'<br>';
                echo 'nb de lignes modifies = '.$loi->ajoutLoi().'<br>';
            }
            catch( PDOException $e ) {
                echo 'Erreur : '.$e->getMessage();
                exit;
            }
        ?>

    </body>
</html>
