<?php
require_once './Loi.php';
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Page test loi</title>
    </head>
    <body>

        <?php
            $loi = new Loi('ordreNaissance','jeune');
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