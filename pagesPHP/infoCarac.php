<?php
require_once '../accesBDD/classesPHP/Arbre.php';

if ($_SESSION['idCarac'] != -1){
    $carac = caracPerso($_SESSION['idCarac']);
    $sexe;
    if($carac['sexe'] == 'homme'){
            $sexe = 'H';
        }
        else{
            $sexe = 'F';
    }
    echo '
    <img id="imgPerso" src="../imagesPersos/'.$sexe.$_SESSION['idCarac'].'.png">
    <div id="affichageTexte">
        <h4>Prénom : '.$carac['prenom'].' </h4>

        <div class="ligne1" id="divPersonnage">
            <p>Parent : '.$carac['parent'].'</p>
            <p>Sexe : '.$carac['sexe'].'</p>
            <p>Age : '.$carac['age'].'</p>';
            if ($carac['ordreNaissance'] == 1) {
              echo '<p>Ordre de Naissance : 1er de sa fraterie </p>';
            }
            else {
              echo  '<p>Ordre de Naissance : ' . $carac['ordreNaissance'].'ème de sa fraterie </p>';
            }
            echo '
            <p>Ordre de Naissance : '.$carac['ordreNaissance'].'</p>
            <p>Religion : '.$carac['religion'].'</p>
            <p>Pays : '.$carac['nationnalite'].'</p>
            <p>Etat de sante : '.$carac['etatSante'].'</p>
            <p>Vivant : '.$carac['estEnVie'].'</p>
        </div>
    </div>
    ';
}
else{
    echo '
    <img id="imgPerso" src="../images/maenele.jpg">
    <div id="affichageTexte">
        <p> Inconnu <p>
    ';
}
