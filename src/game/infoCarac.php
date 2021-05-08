<?php
require_once '../model/classesPHP/Arbre.php';

//Si le joueur a cliquer sur un des personnages on affiche ses caractéristiques
//On vérifie que le personnage est bien dans la table
$result = MyPDO::pdo()->prepare("SELECT * FROM persoDe". $_SESSION['login'] ." WHERE id = :id");
$idSucces = $result->bindValue(':id',$_SESSION['idCarac'], PDO::PARAM_INT);
$result->execute();
$nbLignes = $result->rowCount();

if ($nbLignes == 1){
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
    <img id="imgPerso" src="../../img/imgPersos/'.$sexe.$_SESSION['idCarac'].'.png">
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
    <img id="imgPerso" src="../../img/imgPersos/aucunPerso.png">
    <div id="affichageTexte">
    <h4>Prénom : Inconnu </h4>

    <div class="ligne1" id="divPersonnage">
        <p>Parent : Inconnu </p>
        <p>Sexe : Inconnu </p>
        <p>Age : Inconnu </p>
        <p>Religion : Inconnu </p>
        <p>Pays : Inconnu </p>
        <p>Etat de sante : Inconnu </p>
        <p>Richesse : Inconnu </p>
        <p>Affinité: Inconnu </p>
        <p>Vivant : Inconnu </p>
    </div>
    ';
}
