<?php
require_once('bddT3.php');

class MyPDO {
    private static $_pdo = null;

    private function __construct() {}

    public static function pdo() : PDO {
        if ( self::$_pdo == null )
            self::$_pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));
        return self::$_pdo;
    }

}
