<?php
require_once '../accesBDD/classesPHP/Arbre.php';
require_once '../accesBDD/chercheCaracPerso.php';
if (isset($_GET['id']) && !empty($_GET['id'])){
   $carac = caracPerso($_GET['id']);
}

        if (isset($carac) && !empty($carac)){
            echo '  <div>
                    <p>Identifiant :'.$_GET['id'].' </p>
                </div>

                    <div class="ligne1">
                        <p>Parent : '.$carac['parent'].'</p>
                        <p>Sexe : '.$carac['sexe'].'</p>
                        <p>Age : '.$carac['age'].'</p>
                        <p>Ordre de Naissance : '.$carac['ordreNaissance'].'</p>
                    </div>
                    <div class="ligne2">
                        <p>Religion : '.$carac['religion'].'</p>
                        <p>Pays : '.$carac['nationnalite'].'</p>
                        <p>Etat de sante : '.$carac['etatSante'].'</p>
                        <p>Vivant : '.$carac['estEnVie'].'</p>
                    </div>
                ';
        }
        else{
            echo '  <div>
                    <p>Identifiant : </p>
                </div>

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
                ';
        }
