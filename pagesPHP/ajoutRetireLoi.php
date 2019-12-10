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
        <!-- On créer un formulaire qui renvera des informations sur la loi choisi -->
        <form action="../pagesPHP/mepLoi.php" method="POST">
            <?php
                $Ctrlloi = new CtrlLoi();
                try{
                  /*Rempli la zone de vote du joueur. Deux listes en accordéons par catégories sont créés :
                      Une pour abroger les lois qui sont mises en place.
                      Une pour voter les lois dont aucune de la catéorie n'est est en place.*/
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
