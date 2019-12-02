<?php
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
        <h1>Encyclopédie: </h1>
        <p>Choisissez une caractéristique.</p>
        <ul id="accordion_encyclo">
        <?php
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois");
        $ok = $result->execute();
        $tabParam=['religion'=>'religion','sexe'=>'sexe','ordreNaissance'=>'ordreNaissance','richesse'=>'richesse'];
        $i=1;
        foreach ($tabParam as $param){
                $paramLabel = '';
                if ($param == 'religion') {
                    $paramLabel = 'Religion';
                }
                elseif($param == 'sexe'){
                    $paramLabel = 'Sexe';
                }
                elseif($param == 'richesse'){
                    $paramLabel = 'Richesse';
                }
                else{
                    $paramLabel = 'Ordre de naissance';
                }
                echo' <li>
                <label for="menu'.$i.'">'.$paramLabel.'</label>
                <input id="menu'.$i.'" type="checkbox" name="menu"/>
                <ul class="accordion"><br> ';
                $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE parametre=:param Order by label desc");
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
    </main>
  </body>
</html>

