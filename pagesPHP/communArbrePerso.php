<?php
require_once '../accesBDD/classesPHP/Arbre.php';
require_once '../accesBDD/chercheCaracPerso.php';
if (isset($_GET['id']) && !empty($_GET['id'])){
   $carac = caracPerso($_GET['id']);
}

?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/arbreGenealogique.css">
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
    
    <section id="carac">
        <?php
        if (isset($carac) && !empty($carac)){
            echo '  <div>
                    <p>Identifiant :'.$_GET['id'].' </p>
                </div>
                <main>
                    <div class="ligne1">
                        <p>Parent : '.$carac['parent'].'</p>
                        <p>Sexe : '.$carac['sexe'].'</p>
                        <p>Age : '.$carac['age'].'</p>
                        <p>Ordre de Naissance : '.$carac['ordreNaissance'].'</p>
                    </div>
                    <div class="ligne2">
                        <p>Religion : '.$carac['religion'].'</p>
                        <p>Nationnalite : '.$carac['nationnalite'].'</p>
                        <p>Etat de sante : '.$carac['etatSante'].'</p>
                        <p>Vivant : '.$carac['estEnVie'].'</p>
                    </div>
                </main>';
        }        
        else{
            echo '  <div>
                    <p>Identifiant : </p>
                </div>
                <main>
                    <div class="ligne1">
                        <p>Parent : </p>
                        <p>Sexe : </p>
                        <p>Age : </p>
                        <p>Ordre de Naissance : </p>
                    </div>
                    <div class="ligne2">
                        <p>Religion : </p>
                        <p>Nationnalite : </p>
                        <p>Etat de sante : </p>
                        <p>Vivant : </p>
                    </div>
                </main>';
        }
        ?>

    </section>

    </body>
</html>