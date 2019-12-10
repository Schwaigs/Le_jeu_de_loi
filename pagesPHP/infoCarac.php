<?php
require_once '../accesBDD/classesPHP/Arbre.php';

//Si le joueur a cliquer sur un des personnages on affiche ses caractéristiques
if ($_SESSION['idCarac'] != -1){
    //chercheCaracPerso() Cherche les différentes caractéristiques d'un personnage et les mets en forme.
    $carac = caracPerso($_SESSION['idCarac']);
    $sexe;
    if($carac['sexe'] == 'homme'){
            $sexe = 'H';
        }
        else{
            $sexe = 'F';
    }
    //On rempli la zone d'affichage des caractéristiques
    echo '
    <img id="imgPerso" src="../imagesPersos/'.$sexe.$_SESSION['idCarac'].'.png">
    <div id="affichageTexte">
        <h4>Prénom : '.$carac['prenom'].' </h4>

        <div class="ligne1" id="divPersonnage">
            <p>Parent : '.$carac['parent'].'</p>
            <p>Sexe : '.$carac['sexe'].'</p>
            <p>Age : '.$carac['age'].'</p>';
            if ($carac['ordreNaissance'] == 1) {
              echo '<p>Ordre de Naissance : </p><p> 1er de sa fraterie </p>';
            }
            else {
              echo  '<p>Ordre de Naissance : </p><p>' . $carac['ordreNaissance'].'ème de sa fraterie </p>';
            }
            echo '
            <p>Religion : '.$carac['religion'].'</p>
            <p>Pays : '.$carac['nationnalite'].'</p>
            <p>Etat de sante : '.$carac['etatSante'].'</p>
            <p>Richesse : '.$carac['richesse'].'</p>
            <p>Affinité: '.$carac['affinite'].'</p>
            <p>Vivant : '.$carac['estEnVie'].'</p>
        </div>
    </div>
    ';
}
//Si le joueur n'as cliqué sur aucun personnage lui affiche une image et un texte par défaut
else{
    echo '
    <img id="imgPerso" src="../imagesPersos/aucunPerso.png">
    <div id="affichageTexte">
        <p> Inconnu <p>
    ';
}
