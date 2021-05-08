<?php
require_once '../model/MyPDO.php';
require_once '../model/classesPHP/CtrlLoi.php';
    $ctrlLoi = new CtrlLoi();
    try{
        $ctrlLoi->afficheLoiEnPlace();
    }
    catch( PDOException $e ) {
        echo 'Erreur : '.$e->getMessage();
        exit;
    }
