<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

class CtrlLoi {

    public function __construct(){
    }

    public function afficheLoiEnPlace() : void {
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace=1");
        $ok2 = $result->execute();
        if (0 == $result->rowCount()){
            echo 'Aucune loi est en place pour le moment';
        }
        else{
            /*Afficher la liste des lois que l'utilistateur a choisit */
            foreach ( $result as $row ) {
                echo '<p>'. $row['label'] . '</p>';
            }
        }
    }


    public function remplissageVote() : void {
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace=1");
        $ok = $result->execute();
        $tabParam=['religion'=>'religion','sexe'=>'sexe','ordreNaissance'=>'ordreNaissance'];
        $i=1;
        echo"<h2> Abroger une Loi </h2>";
        if (0 != $result->rowCount()){
            echo"<p>Choisissez une caractéristique.</p>";
            echo '<ul id="accordion_supp">';
            foreach ($result as $row){
                $value='supp '.$row['parametre'].' '.$row['paramVal'];
                unset($tabParam[$row['parametre']]);
                $paramLabel = '';
                if ($row['parametre'] == 'religion') {
                    $paramLabel = 'Religion';
                }
                elseif($row['parametre'] == 'sexe'){
                    $paramLabel = 'Sexe';
                }
                else{
                    $paramLabel = 'Ordre de naissance';
                }
                echo '<li>
                    <label for="menu'.$i.'">'.$paramLabel.'</label>
                    <input id="menu'.$i.'" type="checkbox" name="menu"/>
                    <ul class="accordion_loi">
                        <li><input type="radio" name="Lois" value="'.$value.'">'.$row['label'].'</li>
                    </ul>
                </li>';
                $i++;
            }
        }
        else{
            echo"<p>Aucune loi n'étant mise en place vous ne pouvez pas en abroger.</p>";
        }
        echo"<h2> Voter une Loi </h2>";
        if(!empty($tabParam)){
            echo"<p>Choisissez une caractéristique.</p>";
            echo'<ul id="accordion_ajout">';
            foreach ($tabParam as $param){
                $paramLabel = '';
                if ($param == 'religion') {
                    $paramLabel = 'Religion';
                }
                elseif($param == 'sexe'){
                    $paramLabel = 'Sexe';
                }
                else{
                    $paramLabel = 'Ordre de naissance';
                }
                echo' <li>
                <label for="menu'.$i.'">'.$paramLabel.'</label>
                <input id="menu'.$i.'" type="checkbox" name="menu"/>
                <ul class="accordion_loi">';
                $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE parametre=:param");
                $paramSucces = $result->bindValue(':param',$param, PDO::PARAM_STR);
                $ok2 = $result->execute();
                foreach ( $result as $row ) {
                    $value='ajout '.$param.' '.$row['paramVal'];
                    echo'<li><input type="radio" name="Lois" value="'.$value.'">'.$row['label'].'</li>';
                }
                echo'</ul>
                    </li>';
                $i++;
            }
        }
        else{
            echo"<p>Vous avez déjà mis en place une loi pour chaque caractéritique. Vous ne pouvez donc pas en voter une nouvelle.</p>";
        }
    }

}
