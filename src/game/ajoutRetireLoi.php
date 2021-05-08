<?php
require_once '../model/classesPHP/CtrlLoi.php';
echo '<link rel="stylesheet" href="../css/ajoutRetireLoi.css">';
?>
<!-- On créer un formulaire qui renvera des informations sur la loi choisi -->
<form action="../game/mepLoi.php" method="POST">
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
    <!-- On créer un dernier radio bouton qui permet de ne rien choisir -->
        <h2> Ne rien changer aux lois </h2>
        <ul id="passer">
            <li><input type="radio" name="Lois" value="Passer">Passer</li>
        </ul>
        <br>
    <input type="submit" value="Valider">
</form>
