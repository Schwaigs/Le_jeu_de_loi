<?php 
require_once '../accesBDD/classesPHP/CtrlLoi.php';
?>
<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <title>Lois</title>
    <link rel="stylesheet" href="../css/ajoutRetireLoi.css">
  </head>

  <body>
    <main>
        <form action="mepLoi.php" method="POST">
            <?php
                $Ctrlloi = new CtrlLoi();
                try{
                    $Ctrlloi->remplissageVote();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }
            ?>
            <input type="submit" value="Valider">
        </form>
    </main>
  </body>
</html>