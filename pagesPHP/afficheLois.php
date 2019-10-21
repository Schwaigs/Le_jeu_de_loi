            <?php
            require_once '../accesBDD/MyPDO.php';
            require_once '../accesBDD/classesPHP/CtrlLoi.php';
                $ctrlLoi = new CtrlLoi();
                try{
                    $ctrlLoi->afficheLoiEnPlace();
                }
                catch( PDOException $e ) {
                    echo 'Erreur : '.$e->getMessage();
                    exit;
                }
