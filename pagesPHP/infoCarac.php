<?php
require_once '../accesBDD/classesPHP/Arbre.php';
require_once '../accesBDD/chercheCaracPerso.php';

if (isset($_GET['id']) && !empty($_GET['id'])){
   $carac = caracPerso($_GET['id']);
}
echo'';

if (isset($carac) && !empty($carac)){
    $sexe;
    if($carac['sexe'] == 'homme'){
            $sexe = 'H';
        }
        else{
            $sexe = 'F';
    }
    echo '
    <img id="imgPerso" src="../imagesPersos/'.$sexe.$_GET['id'].'.png">
    <div id="affichageTexte">
        <h4>Prénom : '.$carac['prenom'].' </h4>

        <div class="ligne1" id="divPersonnage">
            <p>Parent : '.$carac['parent'].'</p>
            <p>Sexe : '.$carac['sexe'].'</p>
            <p>Age : '.$carac['age'].'</p>
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
        <h4>Identifiant : </h4>

        <div class="ligne1" id="divPersonnage">
            <p>Parent : </p>
            <p>Sexe : </p>
            <p>Age : </p>
            <p>Ordre de Naissance : </p>
            <p>Religion : </p>
            <p>Nationnalite : </p>
            <p>Etat de sante : </p>
            <p>Vivant : </p>
        </div>
    ';
}
