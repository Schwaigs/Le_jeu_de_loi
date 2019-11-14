<?php
require_once '../accesBDD/classesPHP/Arbre.php';
require_once '../accesBDD/chercheCaracPerso.php';
if (isset($_GET['id']) && !empty($_GET['id'])){
   $carac = caracPerso($_GET['id']);
}

        if (isset($carac) && !empty($carac)){
            echo '
                    <h4>Identifiant :'.$_GET['id'].' </h4>


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
                ';
        }
        else{
            echo '
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
