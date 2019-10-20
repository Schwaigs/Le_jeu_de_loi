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
            echo 'nope';
        }
        else{
            /*Afficher la liste des lois que l'utilistateur peut choisir */
            foreach ( $result as $row ) {
                echo '<p>'. $row['description'] . '</p><br>';
            }
        }
    }

    public function afficheLoiPeutVoter() : void {
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace=0");
        $ok2 = $result->execute();
        if (0 == $result->rowCount()){
            echo 'nope';
        }
        else{
            /*Afficher la liste des lois que l'utilistateur peut choisir */
            foreach ( $result as $row ) {
                echo '<input type="radio" name="Lois" value="' . $row['paramVal'] .'">'. $row['description'] . '<br>';
            }
        }
    }
    

}
