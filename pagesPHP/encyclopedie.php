<?php
session_start();
require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Encyclopédie</title>
    <link rel="stylesheet" href="../css/styleEncyclo.css">
  </head>

  <body>
    <main>
      <div id="texteEncyclo">
        <h1>Encyclopédie </h1>
        <p>Choisissez une caractéristique.</p>
        <!-- On créer une liste sous forme d'accordéon-->
        <ul id="accordion_encyclo">
        <?php
        $result = MyPDO::pdo()->prepare("SELECT * FROM loisDe". $_SESSION['login'] ."");
        $ok = $result->execute();

        //Pour chacune des catégorie de loi possible on créer une sous-liste
        $tabParam=['religion'=>'Religion','sexe'=>'Sexe','ordreNaissance'=>'Ordre de naissance','richesse'=>'Richesse','sante'=>'Santé'];
        $i=1;
        foreach ($tabParam as $param => $paramLabel){
                echo' <li>
                <label for="menu'.$i.'">'.$paramLabel.'</label>
                <input id="menu'.$i.'" type="radio" name="menu"/>
                <ul class="accordion"><br> ';
                //Puis pour chaque catégories on cherche les différentes lois qui y sont associées
                $result = MyPDO::pdo()->prepare("SELECT * FROM loisDe". $_SESSION['login'] ." WHERE parametre=:param Order by label");
                $paramSucces = $result->bindValue(':param',$param, PDO::PARAM_STR);
                $ok2 = $result->execute();
                foreach ( $result as $row ) {
                    echo '<li>'.$row['label'] .' :  '. $row['description'] . '</li>';
                }
                echo'</ul>
                    </li>';
                $i++;
            }
        ?>
      </div>
    </main>
    <div id="quitterEncyclo">
	      <a href="../pageDeLancement/lancement.php" onclick="window.close(this.href); return false;">
	      <!--  <img id="imgQuitterEncyclo" src="../images/Parchemin_titre_gauche.png" width="400" height="100"> -->
	        <p>Retour</p>
	      </a>
	  </div>
  </body>
</html>
